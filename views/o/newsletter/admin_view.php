<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\NewsletterController
 * @var $model ommu\users\models\UserNewsletter
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 14 November 2018, 01:24 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->email;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->newsletter_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="user-newsletter-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'newsletter_id',
		'email:email',
		[
			'attribute' => 'status',
			'value' => $this->quickAction(Url::to(['status', 'id'=>$model->primaryKey]), $model->status, 'Subscribe,Unsubscribe'),
			'format' => 'raw',
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'level_search',
			'value' => isset($model->user->level) ? $model->user->level->title->message : '-',
		],
		[
			'attribute' => 'reference_search',
			'value' => isset($model->reference) ? $model->reference->displayname : '-',
		],
		[
			'attribute' => 'subscribe_search',
			'value' => isset($model->subscribe) ? $model->subscribe->displayname : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
			'visible' => !$small,
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
			'visible' => !$small,
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
			'visible' => !$small,
		],
		'updated_ip',
		[
			'attribute' => 'view.invite_by',
			'value' => isset($model->view) ? $model->view->invite_by : '-',
		],
		[
			'attribute' => 'view.invites',
			'value' => isset($model->view) ? $model->view->invites : '-',
		],
		[
			'attribute' => 'view.invite_all',
			'value' => isset($model->view) ? $model->view->invite_all : '-',
		],
		[
			'attribute' => 'view.invite_users',
			'value' => isset($model->view) ? $model->view->invite_users : '-',
		],
		[
			'attribute' => 'view.invite_user_all',
			'value' => isset($model->view) ? $model->view->invite_user_all : '-',
		],
		[
			'attribute' => 'view.first_invite_date',
			'value' => isset($model->view) ? Yii::$app->formatter->asDatetime($model->view->first_invite_date, 'medium') : '-',
		],
		[
			'attribute' => 'view.first_invite_user_id',
			'value' => isset($model->view->firstInvite) ? $model->view->firstInvite->displayname : '-',
		],
		[
			'attribute' => 'view.last_invite_date',
			'value' => isset($model->view) ? Yii::$app->formatter->asDatetime($model->view->last_invite_date, 'medium') : '-',
		],
		[
			'attribute' => 'view.last_invite_user_id',
			'value' => isset($model->view->lastInvite) ? $model->view->lastInvite->displayname : '-',
		],
		[
			'attribute' => 'view.register',
			'value' => $this->filterYesNo($model->view->register),
		],
	],
]) ?>

</div>