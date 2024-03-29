<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 15 September 2018, 20:52 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
		// 'on_post' => '',
	),
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	*/
)); ?>

<div class="dialog-content">

	<fieldset>

		<?php if(Yii::app()->getRequest()->getParam('id')) {?>
		<div class="intro">
			<?php echo Yii::t('phrase', 'Complete the form below to add/edit this admin account. Note that normal admins will not be able to delete or modify the superadmin account. If you want to change this admin\'s password, enter both the old and new passwords below - otherwise, leave them both blank.');?>
		</div>
		<?php }?>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('displayname')?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model,'displayname', array('maxlength'=>64, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'displayname'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<?php if(!$model->isNewRecord) {?>
		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('first_name')?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model,'first_name', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'first_name'); ?>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('last_name')?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model,'last_name', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'last_name'); ?>
			</div>
		</div>
		<?php }?>

		<?php if($setting->signup_username == 1) {?>
		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('username')?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model,'username', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
		</div>
		<?php }?>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('email')?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model,'email', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'email'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<?php if($setting->signup_photo == 1) {?>
		<div class="form-group row">
			<?php echo $form->labelEx($model,'photos', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->fileField($model,'photos', array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'photos'); ?>
			</div>
		</div>
		<?php }?>
		
		<?php if($model->isNewRecord || (!$model->isNewRecord && Yii::app()->getRequest()->getParam('id'))) {
			if(($model->isNewRecord && $setting->signup_random == 0) || !$model->isNewRecord) {?>
			<div class="form-group row">
				<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('newPassword')?> <?php echo $model->isNewRecord ? '<span class="required">*</span>' : '';?></label>
				<div class="col-lg-8 col-md-8 col-sm-12">
					<?php echo $form->passwordField($model,'newPassword', array('maxlength'=>32, 'class'=>'form-control')); ?>
					<?php echo $form->error($model,'newPassword'); ?>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('confirmPassword')?> <?php echo $model->isNewRecord ? '<span class="required">*</span>' : '';?></label>
				<div class="col-lg-8 col-md-8 col-sm-12">
					<?php echo $form->passwordField($model,'confirmPassword', array('maxlength'=>32, 'class'=>'form-control')); ?>
					<?php echo $form->error($model,'confirmPassword'); ?>
				</div>
			</div>
			<?php }?>

			<?php if(($model->isNewRecord && $setting->signup_approve == 0) || !$model->isNewRecord) {?>
			<div class="form-group row publish">
				<?php echo $form->labelEx($model, 'enabled', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
				<div class="col-lg-8 col-md-8 col-sm-12">
					<?php echo $form->radioButtonList($model, 'enabled', Users::getEnabled(), array('class'=>'form-control')); ?>
					<?php echo $form->error($model, 'enabled'); ?>
				</div>
			</div>
			<?php }?>

			<?php if(($model->isNewRecord && $setting->signup_verifyemail == 1) || !$model->isNewRecord) {?>
			<div class="form-group row publish">
				<?php echo $form->labelEx($model,'verified', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
				<div class="col-lg-8 col-md-8 col-sm-12">
					<?php echo $form->checkBox($model,'verified', array('class'=>'form-control')); ?>
					<?php echo $form->labelEx($model, 'verified'); ?>
					<?php echo $form->error($model,'verified'); ?>
				</div>
			</div>
		<?php }
		}?>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


