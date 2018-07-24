<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 12:25 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Newsletters'=>array('manage'),
		$model->email=>array('view','id'=>$model->newsletter_id),
		'Status',
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-newsletter-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo $model->status == 1 ? Yii::t('phrase', 'Are you sure you want to unsubscribe this item?') : Yii::t('phrase', 'Are you sure you want to subscribe this item?')?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>