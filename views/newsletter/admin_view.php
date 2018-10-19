<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\NewsletterController
 * @var $model ommu\users\models\UserNewsletter
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 15:59 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->newsletter_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'newsletter_id',
		[
			'attribute' => 'status',
			'value' => $this->quickAction(Url::to(['status', 'id'=>$model->primaryKey]), $model->status, 'Subscribe,Unsubscribe'),
			'format' => 'raw',
		],
		[
			'attribute' => 'level_search',
			'value' => isset($model->user->level) ? $model->user->level->title->message : '-',
		],
		[
			'attribute' => 'user_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		'email:email',
		[
			'attribute' => 'reference_search',
			'value' => isset($model->reference) ? $model->reference->displayname : '-',
		],
		[
			'attribute' => 'subscribe_date',
			'value' => !in_array($model->subscribe_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->subscribe_date, 'datetime') : '-',
		],
		[
			'attribute' => 'subscribe_search',
			'value' => isset($model->subscribe) ? $model->subscribe->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
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
			'value' => isset($model->view) ? (!in_array($model->view->first_invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->view->first_invite_date, 'datetime') : '-') : '-',
		],
		[
			'attribute' => 'view.first_invite_user_id',
			'value' => isset($model->view->firstUser) ? $model->view->firstUser->displayname : '-',
		],
		[
			'attribute' => 'view.last_invite_date',
			'value' => isset($model->view) ? (!in_array($model->view->last_invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->view->last_invite_date, 'datetime') : '-') : '-',
		],
		[
			'attribute' => 'view.last_invite_user_id',
			'value' => isset($model->view->lastUser) ? $model->view->lastUser->displayname : '-',
		],
		[
			'attribute' => 'view.register',
			'value' => isset($model->view) ? ($model->view->register == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No')) : '-',
		],
	],
]) ?>