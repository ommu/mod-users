<?php
/**
 * User Settings (user-setting)
 * @var $this SettingController
 * @var $model UserSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 10 August 2017, 13:50 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'user-setting-form',
	'enableAjaxValidation'=>true,
)); ?>
	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>

		<div class="form-group row">
			<label class="col-form-label col-lg-3 col-md-3 col-sm-12">
				<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
				<span><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></span>
			</label>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php if($model->isNewRecord || (!$model->isNewRecord && $model->license == '')) {
					$model->license = $this->licenseCode();
					echo $form->textField($model,'license', array('maxlength'=>32, 'class'=>'form-control'));
				} else
					echo $form->textField($model,'license', array('maxlength'=>32, 'class'=>'form-control','disabled'=>'disabled'));?>
				<?php echo $form->error($model,'license'); ?>
				<div class="small-px"><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></div>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'permission', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<div class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></div>
				<?php if($model->isNewRecord && !$model->getErrors())
					$model->permission = 1;
				echo $form->radioButtonList($model, 'permission', array(
					1 => Yii::t('phrase', 'Yes, the public can view articles unless they are made private.'),
					0 => Yii::t('phrase', 'No, the public cannot view articles.'),
				), array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'permission'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'meta_description', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'meta_description', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model,'meta_description'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'meta_keyword', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'meta_keyword', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model,'meta_keyword'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'forgot_difference', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-3 col-md-4 col-sm-6 col-6">
				<?php echo $form->textField($model,'forgot_difference', array('maxlength'=>2, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'forgot_difference'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
			<div class="col-lg-3 col-md-5 col-sm-6 col-6">
				<?php if($model->isNewRecord && !$model->getErrors())
					$model->forgot_diff_type = 0;
				echo $form->dropDownList($model, 'forgot_diff_type', array(
					1 => Yii::t('phrase', 'Hour'),
					0 => Yii::t('phrase', 'Day'),
				), array('class'=>'form-control')); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'verify_difference', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-3 col-md-4 col-sm-6 col-6">
				<?php echo $form->textField($model,'verify_difference', array('maxlength'=>2, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'verify_difference'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
			<div class="col-lg-3 col-md-5 col-sm-6 col-6">
				<?php if($model->isNewRecord && !$model->getErrors())
					$model->verify_diff_type = 0;
				echo $form->dropDownList($model, 'verify_diff_type', array(
					1 => Yii::t('phrase', 'Hour'),
					0 => Yii::t('phrase', 'Day'),
				), array('class'=>'form-control')); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'invite_difference', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-3 col-md-4 col-sm-6 col-6">
				<?php echo $form->textField($model,'invite_difference', array('maxlength'=>2, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'invite_difference'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
			<div class="col-lg-3 col-md-5 col-sm-6 col-6">
				<?php if($model->isNewRecord && !$model->getErrors())
					$model->invite_diff_type = 0;
				echo $form->dropDownList($model, 'invite_diff_type', array(
					1 => Yii::t('phrase', 'Hour'),
					0 => Yii::t('phrase', 'Day'),
				), array('class'=>'form-control')); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'invite_order', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php if($model->isNewRecord && !$model->getErrors())
					$model->invite_diff_type = 0;
				echo $form->dropDownList($model, 'invite_order', array(
					'asc' => Yii::t('phrase', 'Ascending'),
					'desc' => Yii::t('phrase', 'Descending'),
				), array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'invite_order'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="form-group row submit">
			<label class="col-form-label col-lg-3 col-md-3 col-sm-12">&nbsp;</label>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>


