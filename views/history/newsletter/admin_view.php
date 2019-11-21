<?php
/**
 * User Newsletter Histories (user-newsletter-history)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\history\NewsletterController
 * @var $model ommu\users\models\UserNewsletterHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 7 May 2018, 09:00 WIB
 * @modified date 13 November 2018, 23:44 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Newsletter Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->newsletter->email;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="user-newsletter-history-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'status',
			'value' => $model->status == 1 ? Yii::t('app', 'Subscribe') : Yii::t('app', 'Unsubscribe'),
		],
		[
			'attribute' => 'email_search',
			'value' => isset($model->newsletter) ? Yii::$app->formatter->asEmail($model->newsletter->email) : '-',
			'format' => 'html',
		],
		[
			'attribute' => 'register_search',
			'value' => $this->filterYesNo($model->newsletter->view->register),
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->newsletter->user) ? $model->newsletter->user->displayname : '-',
		],
		[
			'attribute' => 'userLevel',
			'value' => isset($model->newsletter->user->level) ? $model->newsletter->user->level->title->message : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
			'visible' => !$small,
		],
		'updated_ip',
	],
]); ?>

</div>