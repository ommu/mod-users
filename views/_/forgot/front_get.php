<?php
/**
 * User Forgot (user-forgot)
 * @var $this ForgotController
 * @var $model UserForgot
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
 
	$this->breadcrumbs=array(
		'User Forgots'=>array('manage'),
		'Create',
	);

if(Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) {
	if(Yii::app()->getRequest()->getParam('type') == 'success') {
		echo '<a class="button" href="'.Yii::app()->createUrl('site/login').'" title="'.Yii::t('phrase', 'Login').'">'.Yii::t('phrase', 'Login').'</a>';
	}
	
} else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
	));
}?>