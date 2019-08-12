<?php 
/**
 * User Forgot (user-forgot)
 * @var $this ForgotController
 * @var $model UserForgot
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
 
	$this->breadcrumbs=array(
	'User Forgots'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);

if($render == 1) {
	echo '<a class="button" href="'.Yii::app()->createUrl('users/forgot/get').'" title="'.Yii::t('phrase', 'Forgot your password').'">'.Yii::t('phrase', 'Forgot your password').'</a>';
	
} else if($render == 2) {?>
	<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
		'action'=>Yii::app()->controller->createUrl('post', array('id'=>Yii::app()->params['reset_user_id'])),
		'id'=>'users-form',
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
			'class' => 'form',
			'enctype' => 'multipart/form-data',
		),
	)); ?>

		<fieldset>
			<?php
			$photos = Yii::app()->request->baseUrl.'/public/users/default.png';
			if($model->photos != '')
				$photos = Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/users/'.$model->user_id.'/'.$model->photos, 60, 60, 1);?>
			<div class="user-info clearfix">
				<img src="<?php echo $photos;?>" alt="<?php echo $model->photos != '' ? $model->displayname : 'Ommu Platform';?>"/>
				<div>
					<h3><?php echo $model->displayname;?></h3>
					<?php echo $model->email;?>
				</div>
			</div>
			
			<div class="clearfix">
				<?php echo $form->passwordField($model,'newPassword', array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('newPassword'))); ?>
				<?php echo $form->error($model,'newPassword'); ?>
			</div>

			<div class="clearfix">
				<?php echo $form->passwordField($model,'confirmPassword', array('maxlength'=>32, 'placeholder'=>$model->getAttributeLabel('confirmPassword'))); ?>
				<?php echo $form->error($model,'confirmPassword'); ?>
			</div>

			<div class="submit clearfix">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
			</div>

		</fieldset>

	<?php $this->endWidget(); ?>
<?php }?>
