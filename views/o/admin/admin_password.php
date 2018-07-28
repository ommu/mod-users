<?php
/**
 * Users (users)
 * @var $this SiteController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'Users'=>array('manage'),
		'Create',
	);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">
	<?php if(!Yii::app()->getRequest()->getParam('type') || (Yii::app()->getRequest()->getParam('type') != 'success')) {?>
		<fieldset>

			<?php echo $form->errorSummary($model); ?>

			<div class="form-group row">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('oldPassword')?> <span class="required">*</span></label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo $form->passwordField($model,'oldPassword', array('maxlength'=>32,'class'=>'form-control')); ?>
					<?php echo $form->error($model,'oldPassword'); ?>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('newPassword')?> <span class="required">*</span></label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo $form->passwordField($model,'newPassword', array('maxlength'=>32,'class'=>'form-control')); ?>
					<?php echo $form->error($model,'newPassword'); ?>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-lg-3 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('confirmPassword')?> <span class="required">*</span></label>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<?php echo $form->passwordField($model,'confirmPassword', array('maxlength'=>32,'class'=>'form-control')); ?>
					<?php echo $form->error($model,'confirmPassword'); ?>
				</div>
			</div>

		</fieldset>
	<?php } else {?>
		<div class="empty">
			<?php echo Yii::t('phrase', 'Change password success.');?>
		</div>
	<?php }?>
</div>
<div class="dialog-submit">
	<?php if(!Yii::app()->getRequest()->getParam('type') || (Yii::app()->getRequest()->getParam('type') != 'success')) {
		echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()'));
	}?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>
