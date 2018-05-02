<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this app\modules\user\controllers\LevelController
 * @var $model app\modules\user\models\UserLevel
 * @var $form yii\widgets\ActiveForm
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
use yii\widgets\ActiveForm;
use app\components\Utility;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->level_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Message');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Info'), 'url' => Url::to(['update', 'id' => $model->level_id]), 'icon' => 'info'],
	['label' => Yii::t('app', 'User'), 'url' => Url::to(['user', 'id' => $model->level_id]), 'icon' => 'users'],
	['label' => Yii::t('app', 'Message'), 'url' => Url::to(['message', 'id' => $model->level_id]), 'icon' => 'comment'],
	['label' => Yii::t('app', 'View'), 'url' => Url::to(['view', 'id' => $model->level_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->level_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('success'))
		echo Utility::flashMessage(Yii::$app->session->getFlash('success'));
	else if(Yii::$app->session->hasFlash('error'))
		echo Utility::flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>

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
			<?php $form = ActiveForm::begin([
				'options' => [
					'class' => 'form-horizontal form-label-left',
					//'enctype' => 'multipart/form-data',
				],
			]); ?>
			
			<?php //echo $form->errorSummary($model);?>

<?php 
$message_allow = [
	2 => Yii::t('app', 'Everyone - users can send private messages to anyone.'),
	1 => Yii::t('app', 'Friends only - users can send private messages to their friends only.'),
	0 => Yii::t('app', 'Nobody - users cannot send private messages.'),
];
echo $form->field($model, 'message_allow', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12"><span class="small-px">'.Yii::t('app', '
If set to "nobody", none of the other settings on this page will apply. Otherwise, users will have access to their private message inbox and will be able to send each other messages.').'</span>{input}{error}</div>'])
	->radioList($message_allow, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('message_allow'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group">
	<?php echo $form->field($model, 'message_limit', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('message_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<?php 
		$message_limit = [
			5 => 5,
			10 => 10,
			20 => 20,
			30 => 30,
			40 => 40,
			50 => 50,
			100 => 100,
			200 => 200,
			500 => 500,
		];
		if(!$model->getErrors())
			$model->message_limit = unserialize($model->message_limit);
		echo $form->field($model, 'message_limit[inbox]', ['template' => '<span class="small-px mb-10">'.Yii::t('app', 'How many total conversations will users be allowed to store in their inbox and outbox? If a user\'s inbox or outbox is full and a new conversation is started, the oldest conversation will be automatically deleted.').'</span><div class="col-md-4 col-sm-4">{input}{error}</div><div class="col-md-8 col-sm-8 checkbox">'.Yii::t('app', 'conversations in inbox folder.	').'</div>'])
			->dropDownList($message_limit, ['prompt' => ''], ['class'=>'desc pt-10', 'separator' => '<br />'])
			->label($model->getAttributeLabel('message_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

		<?php echo $form->field($model, 'message_limit[outbox]', ['template' => '<div class="col-md-4 col-sm-4">{input}{error}</div><div class="col-md-8 col-sm-8 checkbox">'.Yii::t('app', 'conversations in outbox folder.').'</div>'])
			->dropDownList($message_limit, ['prompt' => ''], ['class'=>'desc pt-10', 'separator' => '<br />'])
			->label($model->getAttributeLabel('message_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>