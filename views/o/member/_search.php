<?php
/**
 * Users (users)
 * @var $this MemberController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
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
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('enabled'); ?><br/>
			<?php echo $form->textField($model,'enabled'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('verified'); ?><br/>
			<?php echo $form->textField($model,'verified'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('level_id'); ?><br/>
			<?php echo $form->textField($model,'level_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('language_id'); ?><br/>
			<?php echo $form->textField($model,'language_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('email'); ?><br/>
			<?php echo $form->textField($model,'email'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('username'); ?><br/>
			<?php echo $form->textField($model,'username'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('first_name'); ?><br/>
			<?php echo $form->textField($model,'first_name'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('last_name'); ?><br/>
			<?php echo $form->textField($model,'last_name'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('displayname'); ?><br/>
			<?php echo $form->textField($model,'displayname'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('password'); ?><br/>
			<?php echo $form->textField($model,'password'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('photos'); ?><br/>
			<?php echo $form->textField($model,'photos'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('salt'); ?><br/>
			<?php echo $form->textField($model,'salt'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('deactivate'); ?><br/>
			<?php echo $form->textField($model,'deactivate'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('search'); ?><br/>
			<?php echo $form->textField($model,'search'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('invisible'); ?><br/>
			<?php echo $form->textField($model,'invisible'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('privacy'); ?><br/>
			<?php echo $form->textField($model,'privacy'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('comments'); ?><br/>
			<?php echo $form->textField($model,'comments'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?><br/>
			<?php echo $form->textField($model,'creation_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_ip'); ?><br/>
			<?php echo $form->textField($model,'creation_ip'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_date'); ?><br/>
			<?php echo $form->textField($model,'lastlogin_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_ip'); ?><br/>
			<?php echo $form->textField($model,'lastlogin_ip'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_from'); ?><br/>
			<?php echo $form->textField($model,'lastlogin_from'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('update_date'); ?><br/>
			<?php echo $form->textField($model,'update_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('update_ip'); ?><br/>
			<?php echo $form->textField($model,'update_ip'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton('Search'); ?>
		</li>
	</ul>
	<div class="clear"></div>
<?php $this->endWidget(); ?>
