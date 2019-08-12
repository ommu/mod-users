<?php
/**
 * User Forgots (user-forgot)
 * @var $this ForgotController
 * @var $model UserForgot
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 August 2017, 06:43 WIB
 * @modified date 24 July 2018, 08:51 WIB
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
			<?php echo $model->getAttributeLabel('code'); ?>
			<?php echo $form->textField($model, 'code', array('maxlength'=>64, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('forgot_date'); ?>
			<?php echo $this->filterDatepicker($model, 'forgot_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('forgot_ip'); ?>
			<?php echo $form->textField($model, 'forgot_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('expired_date'); ?>
			<?php echo $this->filterDatepicker($model, 'expired_date', false); ?>
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
			<?php echo $model->getAttributeLabel('deleted_date'); ?>
			<?php echo $this->filterDatepicker($model, 'deleted_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('expired_search'); ?>
			<?php echo $form->dropDownList($model, 'expired_search', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?>
			<?php echo $form->dropDownList($model, 'publish', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>