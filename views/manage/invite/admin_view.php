<?php
/**
 * User Invites (user-invites)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\manage\InviteController
 * @var $model ommu\users\models\UserInvites
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:27 WIB
 * @modified date 13 November 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="user-invites-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		'displayname',
		[
			'attribute' => 'email_search',
			'value' => isset($model->newsletter) ? Yii::$app->formatter->asEmail($model->newsletter->email) : '-',
			'format' => 'html',
		],
		'code',
		'invites',
		[
			'attribute' => 'inviter_search',
			'value' => isset($model->inviter) ? $model->inviter->displayname : '-',
		],
		[
			'attribute' => 'level_search',
			'value' => isset($model->inviter->level) ? $model->inviter->level->title->message : '-',
		],
		[
			'attribute' => 'invite_date',
			'value' => Yii::$app->formatter->asDatetime($model->invite_date, 'medium'),
		],
		'invite_ip',
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
	],
]) ?>

</div>