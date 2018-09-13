<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this InviteController
 * @var $model UserInviteHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 5 August 2017, 19:31 WIB
 * @modified date 24 July 2018, 06:41 WIB
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
			<?php echo $model->getAttributeLabel('displayname_search'); ?>
			<?php echo $form->textField($model, 'displayname_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('email_search'); ?>
			<?php echo $form->textField($model, 'email_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('userlevel_search'); ?>
			<?php $userlevel = UserLevel::getLevel();
			echo $form->dropDownList($model, 'userlevel_search', $userlevel, array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('code'); ?>
			<?php echo $form->textField($model, 'code', array('maxlength'=>16, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('invite_date'); ?>
			<?php echo $this->filterDatepicker($model, 'invite_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('invite_ip'); ?>
			<?php echo $form->textField($model, 'invite_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('expired_date'); ?>
			<?php echo $this->filterDatepicker($model, 'expired_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('expired_search'); ?>
			<?php echo $form->dropDownList($model, 'expired_search', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>