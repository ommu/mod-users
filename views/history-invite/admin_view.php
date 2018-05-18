<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\HistoryInviteController
 * @var $model ommu\users\models\UserInviteHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 09:01 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Invite Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'invite_search',
			'value' => isset($model->invite) ? $model->invite->newsletter->user->username : '-',
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
			'value' => isset($model->view) ? ($model->view->expired == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No')) : '-',
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