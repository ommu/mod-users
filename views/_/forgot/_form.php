<?php
/**
 * User Forgot (user-forgot)
 * @var $this ForgotController
 * @var $model UserForgot
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'user-forgot-form',
	'enableAjaxValidation'=>true,
)); ?>
	<fieldset class="users-forgot">
		<div class="clearfix">
			<?php echo $form->labelEx($model,'email_i'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'email_i', array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('email_i'))); ?>
				<?php echo $form->error($model,'email_i'); ?>
			</div>
		</div>
		<div class="clearfix">
			<label></label>
			<div class="desc">
				<?php echo CHtml::submitButton(Yii::t('phrase', 'Get Password'), array('onclick' => 'setEnableSave()', 'class'=>'blue-button')); ?>
			</div>
		</div>
	</fieldset>
<?php $this->endWidget(); ?>