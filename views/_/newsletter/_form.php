<?php
/**
 * User Newsletter (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
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
    'id'=>'support-newsletter-form', 
    'enableAjaxValidation'=>true, 
    //'htmlOptions' => array('enctype' => 'multipart/form-data') 
)); ?>
	<fieldset>
		<div class="clearfix">
			<?php if($launch == 2)
				$model->unsubscribe = 1;
			else {
				$model->unsubscribe = 0;
			}
			echo $form->hiddenField($model,'unsubscribe');
			?>
			<?php echo $form->textField($model,'email', array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('email'), 'class'=>'span-9')); ?><?php echo CHtml::submitButton($launch == 0 ? Yii::t('phrase', 'Notify Me!') : ($launch == 1 ? Yii::t('phrase', 'Subscribe') : Yii::t('phrase', 'Unsubscribe')), array('onclick' => 'setEnableSave()')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>
