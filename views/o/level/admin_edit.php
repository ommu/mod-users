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

		<h3><?php echo Yii::t('phrase', 'Level Settings');?></h3>
		<fieldset>

			<div class="intro">
				<?php echo Yii::t('phrase', 'To modify this user level, complete the following form.');?>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('name_i');?> <span class="required">*</span></label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo $form->textField($model,'name_i', array('maxlength'=>32, 'class'=>'form-control')); ?>
					<?php echo $form->error($model,'name_i'); ?>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('desc_i');?> <span class="required">*</span></label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo $form->textArea($model,'desc_i', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
					<?php echo $form->error($model,'desc_i'); ?>
				</div>
			</div>

			<div class="form-group row publish">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('default');?> <span class="required">*</span></label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo $form->checkBox($model,'default', array('class'=>'form-control')); ?>
					<?php echo $form->labelEx($model, 'default'); ?>
					<?php echo $form->error($model,'default'); ?>
				</div>
			</div>

			<div class="form-group row submit">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12">&nbsp;</label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
				</div>
			</div>

		</fieldset>

	<?php $this->endWidget(); ?>
</div>


