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
 * @modified date 24 July 2018, 09:37 WIB
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
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('reference_search'); ?>
			<?php echo $form->textField($model, 'reference_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('email'); ?>
			<?php echo $form->textField($model, 'email', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('subscribe_date'); ?>
			<?php echo $this->filterDatepicker($model, 'subscribe_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('subscribe_search'); ?>
			<?php echo $form->textField($model, 'subscribe_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?>
			<?php echo $this->filterDatepicker($model, 'modified_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_search'); ?>
			<?php echo $form->textField($model, 'modified_search', array('class'=>'form-control')); ?>
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