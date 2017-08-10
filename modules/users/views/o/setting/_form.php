<?php
/**
 * User Settings (user-setting)
 * @var $this SettingController
 * @var $model UserSetting
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 10 August 2017, 13:50 WIB
 * @link @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'user-setting-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<fieldset>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'license'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'license',array('size'=>32,'maxlength'=>32)); ?>
			<?php echo $form->error($model,'license'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'permission'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'permission'); ?>
			<?php echo $form->labelEx($model,'permission'); ?>
			<?php echo $form->error($model,'permission'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'meta_keyword'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'meta_keyword',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'meta_keyword'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'meta_description'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'meta_description',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'meta_description'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'forgot_diff_type'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'forgot_diff_type',array('size'=>1,'maxlength'=>1)); ?>
			<?php echo $form->error($model,'forgot_diff_type'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'forgot_difference'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'forgot_difference'); ?>
			<?php echo $form->error($model,'forgot_difference'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'verify_diff_type'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'verify_diff_type',array('size'=>1,'maxlength'=>1)); ?>
			<?php echo $form->error($model,'verify_diff_type'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'verify_difference'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'verify_difference'); ?>
			<?php echo $form->error($model,'verify_difference'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'invite_diff_type'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'invite_diff_type',array('size'=>1,'maxlength'=>1)); ?>
			<?php echo $form->error($model,'invite_diff_type'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'invite_difference'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'invite_difference'); ?>
			<?php echo $form->error($model,'invite_difference'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'invite_order'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'invite_order',array('size'=>4,'maxlength'=>4)); ?>
			<?php echo $form->error($model,'invite_order'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'modified_date'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'modified_date'); ?>
			<?php echo $form->error($model,'modified_date'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'modified_id'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'modified_id',array('size'=>11,'maxlength'=>11)); ?>
			<?php echo $form->error($model,'modified_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>

<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


