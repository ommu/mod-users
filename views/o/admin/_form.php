<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $model Users
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
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">

	<fieldset>

		<?php if(isset($_GET['id'])) {?>
		<div class="intro">
			<?php echo Phrase::trans(16104,1);?>
		</div>
		<?php }?>

		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('first_name')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($model,'first_name',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($model,'first_name'); ?>
			</div>
		</div>

		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('last_name')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($model,'last_name',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($model,'last_name'); ?>
			</div>
		</div>

		<?php if(!$model->isNewRecord) {?>
		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('displayname')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($model,'displayname',array('maxlength'=>64,'class'=>'span-7')); ?>
				<?php echo $form->error($model,'displayname'); ?>
			</div>
		</div>
		<?php }?>

		<?php if($setting->signup_username == 1) {?>
		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('username')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($model,'username',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
		</div>
		<?php }?>

		<div class="clearfix">
			<label><?php echo $model->getAttributeLabel('email')?> <span class="required">*</span></label>
			<div class="desc">
				<?php echo $form->textField($model,'email',array('maxlength'=>32,'class'=>'span-7')); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>
		
		<?php if($model->isNewRecord || (!$model->isNewRecord && isset($_GET['id']))) {
			if(($model->isNewRecord && $setting->signup_random == 0) || !$model->isNewRecord) {?>
			<div class="clearfix">
				<label><?php echo $model->getAttributeLabel('newPassword')?> <?php echo $model->isNewRecord ? '<span class="required">*</span>' : '';?></label>
				<div class="desc">
					<?php echo $form->passwordField($model,'newPassword',array('maxlength'=>32,'class'=>'span-7')); ?>
					<?php echo $form->error($model,'newPassword'); ?>
				</div>
			</div>

			<div class="clearfix">
				<label><?php echo $model->getAttributeLabel('confirmPassword')?> <?php echo $model->isNewRecord ? '<span class="required">*</span>' : '';?></label>
				<div class="desc">
					<?php echo $form->passwordField($model,'confirmPassword',array('maxlength'=>32,'class'=>'span-7')); ?>
					<?php echo $form->error($model,'confirmPassword'); ?>
				</div>
			</div>
			<?php }?>

			<?php if(($model->isNewRecord && $setting->signup_approve == 1) || !$model->isNewRecord) {?>
			<div class="clearfix publish">
				<?php echo $form->labelEx($model,'enabled'); ?>
				<div class="desc">
					<?php echo $form->checkBox($model,'enabled'); ?>
					<?php echo $form->labelEx($model,'enabled'); ?>
					<?php echo $form->error($model,'enabled'); ?>
				</div>
			</div>
			<?php }?>

			<?php if(($model->isNewRecord && $setting->signup_verifyemail == 1) || !$model->isNewRecord) {?>
			<div class="clearfix publish">
				<?php echo $form->labelEx($model,'verified'); ?>
				<div class="desc">
					<?php echo $form->checkBox($model,'verified'); ?>
					<?php echo $form->labelEx($model,'verified'); ?>
					<?php echo $form->error($model,'verified'); ?>
				</div>
			</div>
		<?php }
		}?>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Phrase::trans(1,0) : Phrase::trans(2,0) ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Phrase::trans(4,0), array('id'=>'closed')); ?>
</div>

<?php $this->endWidget(); ?>
