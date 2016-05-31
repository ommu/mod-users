<?php
/**
 * User Level (user-level)
 * @var $this LevelController
 * @var $model UserLevel
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'User Levels'=>array('manage'),
		$model->name=>array('view','id'=>$model->level_id),
		'Update',
	);
?>

<div class="form" name="post-on">
	<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
		'id'=>'user-level-form',
		'enableAjaxValidation'=>true,
		//'htmlOptions' => array('enctype' => 'multipart/form-data')
	)); ?>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<h3><?php echo Phrase::trans(16077,1);?></h3>
		<fieldset>

			<div class="intro">
				<?php echo Phrase::trans(16078,1);?>
			</div>

			<div class="clearfix">
				<?php echo $form->labelEx($model,'message_allow'); ?>
				<div class="desc">
					<span class="small-px"><?php echo Phrase::trans(16080,1);?></span>
					<?php echo $form->radioButtonList($model, 'message_allow', array(
						2 => Phrase::trans(16083,1),
						1 => Phrase::trans(16082,1),
						0 => Phrase::trans(16081,1),
					)); ?>
					<?php echo $form->error($model,'message_allow'); ?>
				</div>
			</div>

			<div class="clearfix">
				<label><?php echo Phrase::trans(16084,1);?></label>
				<div class="desc">
					<span class="small-px"><?php echo Phrase::trans(16085,1);?></span>
					<?php echo $form->dropDownList($model, 'message_inbox', array(
						5 => 5,
						10 => 10,
						20 => 20,
						30 => 30,
						40 => 40,
						50 => 50,
						100 => 100,
						200 => 200,
						500 => 500,
					)); ?>
					<?php echo Phrase::trans(16086,1);?>
					<?php echo $form->error($model,'message_inbox'); ?>
					<br/>
					<?php echo $form->dropDownList($model, 'message_outbox', array(
						5 => 5,
						10 => 10,
						20 => 20,
						30 => 30,
						40 => 40,
						50 => 50,
						100 => 100,
						200 => 200,
						500 => 500,
					)); ?>
					<?php echo Phrase::trans(16087,1);?>
					<?php echo $form->error($model,'message_outbox'); ?>
				</div>
			</div>

			<div class="submit clearfix">
				<label>&nbsp;</label>
				<div class="desc">
					<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
				</div>
			</div>

		</fieldset>

	<?php $this->endWidget(); ?>
</div>