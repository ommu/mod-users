<?php
/**
 * User Verifies (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 August 2017, 06:44 WIB
 * @modified date 24 July 2018, 08:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		$model->user->displayname=>array('view','id'=>$model->verify_id),
		Yii::t('phrase', 'Publish'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-verify-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo $model->publish == 1 ? Yii::t('phrase', 'Are you sure you want to unpublish this item?') : Yii::t('phrase', 'Are you sure you want to publish this item?')?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>