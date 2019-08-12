<?php
/**
 * User Newsletter Histories (user-newsletter-history)
 * @var $this SubscribeController
 * @var $model UserNewsletterHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 06:42 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('level_search'); ?>
			<?php $userlevel = UserLevel::getLevel();
			echo $form->textField($model, 'level_search', $userlevel, array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>
		
		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('email_search'); ?>
			<?php echo $form->textField($model, 'email_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_date'); ?>
			<?php echo $this->filterDatepicker($model, 'updated_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_ip'); ?>
			<?php echo $form->textField($model, 'updated_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('status'); ?>
			<?php echo $form->dropDownList($model, 'status', array('1'=>Yii::t('phrase', 'Subscribe'), '0'=>Yii::t('phrase', 'Unsubscribe')), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>