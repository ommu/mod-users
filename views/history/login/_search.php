<?php
/**
 * User History Logins (user-history-login)
 * @var $this LoginController
 * @var $model UserHistoryLogin
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 23 July 2018, 22:51 WIB
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
			<?php $userlevel = UserLevel::getUserLevel();
			echo $form->textField($model, 'level_search', $userlevel, array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>
		
		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_date'); ?>
			<?php echo $this->filterDatepicker($model, 'lastlogin_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_ip'); ?>
			<?php echo $form->textField($model, 'lastlogin_ip', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_from'); ?>
			<?php echo $form->textField($model, 'lastlogin_from', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>