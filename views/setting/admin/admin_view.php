<?php
/**
 * User Settings (user-setting)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\setting\AdminController
 * @var $model ommu\users\models\UserSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 8 November 2018, 12:47 WIB
 * @modified date 9 November 2018, 07:13 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\users\models\UserSetting;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Settings'), 'url' => ['index']];

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil'],
];
?>

<div class="user-setting-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		'license',
		[
			'attribute' => 'permission',
			'value' => UserSetting::getPermission($model->permission),
		],
		[
			'attribute' => 'meta_description',
			'value' => $model->meta_description ? $model->meta_description : '-',
		],
		[
			'attribute' => 'meta_keyword',
			'value' => $model->meta_keyword ? $model->meta_keyword : '-',
		],
		[
			'attribute' => 'forgot_diff_type',
			'value' => UserSetting::getForgotDiffType($model->forgot_diff_type),
		],
		'forgot_difference',
		[
			'attribute' => 'verify_diff_type',
			'value' => UserSetting::getForgotDiffType($model->verify_diff_type),
		],
		'verify_difference',
		[
			'attribute' => 'invite_diff_type',
			'value' => UserSetting::getForgotDiffType($model->invite_diff_type),
		],
		'invite_difference',
		[
			'attribute' => 'invite_order',
			'value' => UserSetting::getInviteOrder($model->invite_order),
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
	],
]) ?>

</div>