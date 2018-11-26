<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\history\InviteController
 * @var $model ommu\users\models\UserInviteHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 13 November 2018, 11:54 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invite Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->invite->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'method'=>'post', 'icon' => 'trash'],
];
?>

<div class="user-invite-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'email_search',
			'value' => isset($model->invite->newsletter) ? $model->invite->newsletter->email : '-',
		],
		[
			'attribute' => 'displayname_search',
			'value' => isset($model->invite) ? $model->invite->displayname : '-',
		],
		[
			'attribute' => 'inviter_search',
			'value' => isset($model->invite->inviter) ? $model->invite->inviter->displayname : '-',
		],
		[
			'attribute' => 'level_search',
			'value' => isset($model->invite->inviter->level) ? $model->invite->inviter->level->title->message : '-',
		],
		'code',
		[
			'attribute' => 'invite_date',
			'value' => !in_array($model->invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->invite_date, 'datetime') : '-',
		],
		'invite_ip',
		[
			'attribute' => 'expired_date',
			'value' => !in_array($model->expired_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->expired_date, 'datetime') : '-',
		],
		[
			'attribute' => 'view.expired',
			'value' => $this->filterYesNo($model->view->expired),
		],
		[
			'attribute' => 'view.verify_day_left',
			'value' => isset($model->view) ? $model->view->verify_day_left : '-',
		],
		[
			'attribute' => 'view.verify_hour_left',
			'value' => isset($model->view) ? $model->view->verify_hour_left : '-',
		],
	],
]) ?>

</div>