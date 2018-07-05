<?php
/**
 * User Invites (user-invites)
 * @var $this InviteController
 * @var $model UserInvites
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'user-invites-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>
		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('email_i');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'email_i', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model,'email_i'); ?>
			</div>
		</div>
		
		<div class="form-group row publish">
			<?php echo $form->labelEx($model,'multiple_email_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model,'multiple_email_i', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'multiple_email_i'); ?>
				<?php echo $form->error($model,'multiple_email_i'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>
