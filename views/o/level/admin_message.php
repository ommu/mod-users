<?php
/**
 * User Levels (user-level)
 * @var $this LevelController
 * @var $model UserLevel
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Levels'=>array('manage'),
		$model->title->message=>array('view','id'=>$model->level_id),
		'Update',
	);
?>

<div class="form" name="post-on">
	<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
		'id'=>'user-level-form',
		'enableAjaxValidation'=>true,
		//'htmlOptions' => array('enctype' => 'multipart/form-data')
	)); ?>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<h3><?php echo Yii::t('phrase', 'Message Settings');?></h3>
		<fieldset>

			<div class="intro">
				<?php echo Yii::t('phrase', 'Facilitating user interactivity is the key to developing a successful social network. Allowing private messages between users is an excellent way to increase interactivity. From this page, you can enable the private messaging feature and configure its settings.');?>
			</div>

			<div class="form-group row">
				<?php echo $form->labelEx($model,'message_allow', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<span class="small-px"><?php echo Yii::t('phrase', 'If set to "nobody", none of the other settings on this page will apply. Otherwise, users will have access to their private message inbox and will be able to send each other messages.');?></span>
					<?php echo $form->radioButtonList($model, 'message_allow', array(
						2 => Yii::t('phrase', 'Everyone - users can send private messages to anyone.'),
						1 => Yii::t('phrase', 'Friends only - users can send private messages to their friends only.'),
						0 => Yii::t('phrase', 'Nobody - users cannot send private messages.'),
					), array('class'=>'form-control')); ?>
					<?php echo $form->error($model,'message_allow'); ?>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo Yii::t('phrase', 'Inbox/Outbox Capacity');?></label>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<span class="small-px"><?php echo Yii::t('phrase', 'How many total conversations will users be allowed to store in their inbox and outbox? If a user\'s inbox or outbox is full and a new conversation is started, the oldest conversation will be automatically deleted.');?></span>
					<div class="row">
						<div class="col-sm-4">
							<?php 
							if(!$model->getErrors())
								$model->message_limit = unserialize($model->message_limit);
							echo $form->dropDownList($model, 'message_limit[inbox]', array(
								5 => 5,
								10 => 10,
								20 => 20,
								30 => 30,
								40 => 40,
								50 => 50,
								100 => 100,
								200 => 200,
								500 => 500,
							), array('class'=>'form-control')); ?>
						</div>
						<div class="col-sm-8"><?php echo Yii::t('phrase', 'conversations in inbox folder.');?></div>
					</div>
					<?php echo $form->error($model,'message_limit[inbox]'); ?>
					<div class="row">
						<div class="col-sm-4">
							<?php echo $form->dropDownList($model, 'message_limit[outbox]', array(
								5 => 5,
								10 => 10,
								20 => 20,
								30 => 30,
								40 => 40,
								50 => 50,
								100 => 100,
								200 => 200,
								500 => 500,
							), array('class'=>'form-control')); ?>
						</div>
						<div class="col-sm-8"><?php echo Yii::t('phrase', 'conversations in outbox folder.');?></div>
					</div>
					<?php echo $form->error($model,'message_limit[outbox]'); ?>
				</div>
			</div>

			<div class="form-group row submit">
				<label class="col-form-label col-lg-4 col-md-3 col-sm-12">&nbsp;</label>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
				</div>
			</div>

		</fieldset>

	<?php $this->endWidget(); ?>
</div>