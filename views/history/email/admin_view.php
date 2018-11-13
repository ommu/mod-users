<?php
/**
 * User History Emails (user-history-email)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\history\EmailController
 * @var $model ommu\users\models\UserHistoryEmail
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 May 2018, 02:17 WIB
 * @modified date 13 November 2018, 00:03 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'History Emails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->user->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="user-history-email-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'level_search',
			'value' => isset($model->user) ? $model->user->level->title->message : '-',
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		'email:email',
		[
			'attribute' => 'update_date',
			'value' => !in_array($model->update_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->update_date, 'datetime') : '-',
		],
	],
]) ?>

</div>