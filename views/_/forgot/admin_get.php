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

if(Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) {?>
	<div class="users-forgot">
		<div><?php echo $desc;?></div>
		<?php if(Yii::app()->getRequest()->getParam('type') == 'success') {
			echo '<a class="button blue-button" href="'.Yii::app()->createUrl('site/login').'" title="'.Yii::t('phrase', 'Login').'">'.Yii::t('phrase', 'Login').'</a>';
		}?>
	</div>

<?php } else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
	));
}?>