<?php
/**
 * User Verify (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'user-verify-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
	<fieldset class="users-forgot">
		<div class="clearfix">
			<?php echo $form->labelEx($model,'email'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'email',array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('email'))); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>
		<div class="clearfix">
			<label></label>
			<div class="desc">
				<?php echo CHtml::submitButton('Get Verify Code', array('onclick' => 'setEnableSave()', 'class'=>'blue-button')); ?>
			</div>
		</div>
	</fieldset>
<?php $this->endWidget(); ?>
