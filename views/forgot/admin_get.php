<?php
/**
 * User Forgot (user-forgot)
 * @var $this ForgotController
 * @var $model UserForgot
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'User Forgots'=>array('manage'),
		'Create',
	);

if(isset($_GET['name']) && isset($_GET['email'])) {?>
	<div class="users-forgot">
		<div><?php echo $desc;?></div>
		<?php if(isset($_GET['type']) && $_GET['type'] == 'success') {
			echo '<a class="button blue-button" href="'.Yii::app()->createUrl('site/login').'" title="'.Yii::t('phrase', 'Login').'">'.Yii::t('phrase', 'Login').'</a>';
		}?>
	</div>	

<?php } else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
	));
}?>