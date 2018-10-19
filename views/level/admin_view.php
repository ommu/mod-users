<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\LevelController
 * @var $model ommu\users\models\UserLevel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 4 May 2018, 09:02 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Info'), 'url' => Url::to(['update', 'id' => $model->level_id]), 'icon' => 'info'],
	['label' => Yii::t('app', 'User'), 'url' => Url::to(['user', 'id' => $model->level_id]), 'icon' => 'users'],
	['label' => Yii::t('app', 'Message'), 'url' => Url::to(['message', 'id' => $model->level_id]), 'icon' => 'comment'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->level_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->level_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'level_id',
		[
			'attribute' => 'name_i',
			'value' => isset($model->title) ? $model->title->message : '-',
		],
		[
			'attribute' => 'desc_i',
			'value' => isset($model->description) ? $model->description->message : '-',
		],
		[
			'attribute' => 'default',
			'value' => $this->quickAction(Url::to(['default', 'id'=>$model->primaryKey]), $model->default, 'Default,No', true),
			'format' => 'raw',
		],
		[
			'attribute' => 'signup',
			'value' => $this->quickAction(Url::to(['signup', 'id'=>$model->primaryKey]), $model->default, 'Enable,Disable'),
			'format' => 'raw',
		],
		[
			'attribute' => 'message_allow',
			'value' => $model->message_allow == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'message_limit',
			'value' => serialize($model->message_limit),
		],
		[
			'attribute' => 'profile_block',
			'value' => $model->profile_block == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_search',
			'value' => $model->profile_search == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_privacy',
			'value' => serialize($model->profile_privacy),
		],
		[
			'attribute' => 'profile_comments',
			'value' => serialize($model->profile_comments),
		],
		[
			'attribute' => 'profile_style',
			'value' => $model->profile_style == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_style_sample',
			'value' => $model->profile_style_sample == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_status',
			'value' => $model->profile_status == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_invisible',
			'value' => $model->profile_invisible == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_views',
			'value' => $model->profile_views == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_change',
			'value' => $model->profile_change == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'profile_delete',
			'value' => $model->profile_delete == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'photo_allow',
			'value' => $model->photo_allow == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
		],
		[
			'attribute' => 'photo_size',
			'value' => serialize($model->photo_size),
		],
		[
			'attribute' => 'photo_exts',
			'value' => $model->photo_exts,
		],
		[
			'attribute' => 'creation_date',
			'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
		],
		[
			'attribute' => 'creation_search',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		'slug',
	],
]) ?>