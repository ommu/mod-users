<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'Login',
	);
?>

<?php //begin.Content ?>
<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
	),
)); ?>
	<fieldset>
		<?php if(!Yii::app()->getRequest()->getParam('email')) {?>
		<div class="clearfix">
			<?php echo $form->textField($model,'email', array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('email'))); ?><?php echo CHtml::submitButton('Check Email' , array('onclick' => 'setEnableSave()')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
		
		<?php } else {
			$model->email = Yii::app()->getRequest()->getParam('email');
			echo $form->hiddenField($model,'email');
		?>
		<div class="clearfix">
			<?php echo $form->passwordField($model,'password', array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('password'))); ?><?php echo CHtml::submitButton('Login' , array('onclick' => 'setEnableSave()')); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
		<?php }?>
		
	</fieldset>

<?php $this->endWidget(); ?>
<?php //end.Content ?>