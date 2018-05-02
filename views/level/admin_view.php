<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this app\coremodules\user\controllers\LevelController
 * @var $model app\coremodules\user\models\UserLevel
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 07:46 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\libraries\MenuContent;
use yii\widgets\DetailView;
use app\components\Utility;

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

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<?php if($this->params['menu']['content']):
			echo MenuContent::widget(['items' => $this->params['menu']['content']]);
			endif;?>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo DetailView::widget([
				'model' => $model,
				'options' => [
					'class'=>'table table-striped detail-view',
				],
				'attributes' => [
					'level_id',
					[
						'attribute' => 'name',
						'value' => $model->name ? $model->title->message : '-',
					],
					[
						'attribute' => 'desc',
						'value' => $model->desc ? $model->description->message : '-',
					],
					[
						'attribute' => 'default',
						'value' => $model->default == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
					],
					[
						'attribute' => 'signup',
						'value' => $model->signup == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
					],
					[
						'attribute' => 'message_allow',
						'value' => $model->message_allow == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
					],
					'message_limit:ntext',
					[
						'attribute' => 'profile_block',
						'value' => $model->profile_block == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
					],
					[
						'attribute' => 'profile_search',
						'value' => $model->profile_search == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
					],
					'profile_privacy:ntext',
					'profile_comments:ntext',
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
					'photo_size:ntext',
					[
						'attribute' => 'photo_exts',
						'value' => Utility::formatFileType(unserialize($model->photo_exts), false),
					],
					[
						'attribute' => 'creation_date',
						'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
					],
					[
						'attribute' => 'creation_search',
						'value' => $model->creation_id ? $model->creation->displayname : '-',
					],
					[
						'attribute' => 'modified_date',
						'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
					],
					[
						'attribute' => 'modified_search',
						'value' => $model->modified_id ? $model->modified->displayname : '-',
					],
					'slug',
				],
			]) ?>
		</div>
	</div>
</div>