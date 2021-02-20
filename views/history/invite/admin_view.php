<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\history\InviteController
 * @var $model ommu\users\models\UserInviteHistory
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 13 November 2018, 11:54 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invite Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->invite->displayname;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="user-invite-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'email_search',
			'value' => isset($model->invite->newsletter) ? Yii::$app->formatter->asEmail($model->invite->newsletter->email) : '-',
			'format' => 'html',
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
			'attribute' => 'userLevel',
			'value' => isset($model->invite->inviter->level) ? $model->invite->inviter->level->title->message : '-',
		],
		'code',
		[
			'attribute' => 'invite_date',
			'value' => Yii::$app->formatter->asDatetime($model->invite_date, 'medium'),
		],
		'invite_ip',
		[
			'attribute' => 'expired_date',
			'value' => Yii::$app->formatter->asDatetime($model->expired_date, 'medium'),
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
]); ?>

</div>