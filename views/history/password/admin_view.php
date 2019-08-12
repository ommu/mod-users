<?php
/**
 * User History Passwords (user-history-password)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\history\PasswordController
 * @var $model ommu\users\models\UserHistoryPassword
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 5 May 2018, 02:18 WIB
 * @modified date 13 November 2018, 01:17 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'History Passwords'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->user->displayname;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="user-history-password-view">

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
		[
			'attribute' => 'email_search',
			'value' => isset($model->user) ? Yii::$app->formatter->asEmail($model->user->email) : '-',
			'format' => 'html',
		],
		'password',
		[
			'attribute' => 'update_date',
			'value' => Yii::$app->formatter->asDatetime($model->update_date, 'medium'),
		],
	],
]) ?>

</div>