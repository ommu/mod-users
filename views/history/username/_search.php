<?php
/**
 * User History Usernames (user-history-username)
 * @var $this UsernameController
 * @var $model UserHistoryUsername
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('id'); ?><br/>
			<?php echo $form->textField($model,'id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('username'); ?><br/>
			<?php echo $form->textField($model,'username'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('update_date'); ?><br/>
			<?php echo $form->textField($model,'update_date'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton('Search'); ?>
		</li>
	</ul>
	<div class="clear"></div>
<?php $this->endWidget(); ?>
