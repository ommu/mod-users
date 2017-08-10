<?php
/**
 * User Settings (user-setting)
 * @var $this SettingController
 * @var $model UserSetting
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 10 August 2017, 13:50 WIB
 * @link @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('id'); ?><br/>
			<?php echo $form->textField($model,'id'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('license'); ?><br/>
			<?php echo $form->textField($model,'license'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('permission'); ?><br/>
			<?php echo $form->textField($model,'permission'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('meta_keyword'); ?><br/>
			<?php echo $form->textField($model,'meta_keyword'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('meta_description'); ?><br/>
			<?php echo $form->textField($model,'meta_description'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('forgot_diff_type'); ?><br/>
			<?php echo $form->textField($model,'forgot_diff_type'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('forgot_difference'); ?><br/>
			<?php echo $form->textField($model,'forgot_difference'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('verify_diff_type'); ?><br/>
			<?php echo $form->textField($model,'verify_diff_type'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('verify_difference'); ?><br/>
			<?php echo $form->textField($model,'verify_difference'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('invite_diff_type'); ?><br/>
			<?php echo $form->textField($model,'invite_diff_type'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('invite_difference'); ?><br/>
			<?php echo $form->textField($model,'invite_difference'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('invite_order'); ?><br/>
			<?php echo $form->textField($model,'invite_order'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id'); ?><br/>
					</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
