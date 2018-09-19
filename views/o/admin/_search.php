<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 September 2018, 20:23 WIB
 * @modified date 15 September 2018, 20:23 WIB
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
			<?php echo $model->getAttributeLabel('level_id'); ?>
			<?php echo $form->dropDownList($model, 'level_id', UserLevel::getLevel(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('language_id'); ?>
			<?php echo $form->dropDownList($model, 'language_id', OmmuLanguages::getLanguage(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('email'); ?>
			<?php echo $form->textField($model, 'email', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('username'); ?>
			<?php echo $form->textField($model, 'username', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('first_name'); ?>
			<?php echo $form->textField($model, 'first_name', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('last_name'); ?>
			<?php echo $form->textField($model, 'last_name', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('displayname'); ?>
			<?php echo $form->textField($model, 'displayname', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('photos'); ?>
			<?php echo $form->textField($model, 'photos', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('salt'); ?>
			<?php echo $form->textField($model, 'salt', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?>
			<?php echo $this->filterDatepicker($model, 'creation_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_ip'); ?>
			<?php echo $form->textField($model, 'creation_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
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
			<?php echo $model->getAttributeLabel('lastlogin_date'); ?>
			<?php echo $this->filterDatepicker($model, 'lastlogin_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_ip'); ?>
			<?php echo $form->textField($model, 'lastlogin_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('lastlogin_from'); ?>
			<?php echo $form->textField($model, 'lastlogin_from', array('maxlength'=>32, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('update_date'); ?>
			<?php echo $this->filterDatepicker($model, 'update_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('update_ip'); ?>
			<?php echo $form->textField($model, 'update_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('enabled'); ?>
			<?php echo $form->dropDownList($model, 'enabled', Users::getEnabled(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('verified'); ?>
			<?php echo $form->dropDownList($model, 'verified', array('1'=>Yii::t('phrase', 'Verified'), '0'=>Yii::t('phrase', 'Unverified')), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('deactivate'); ?>
			<?php echo $form->dropDownList($model, 'deactivate', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('search'); ?>
			<?php echo $form->dropDownList($model, 'search', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('invisible'); ?>
			<?php echo $form->dropDownList($model, 'invisible', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('privacy'); ?>
			<?php echo $form->dropDownList($model, 'privacy', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('comments'); ?>
			<?php echo $form->dropDownList($model, 'comments', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>