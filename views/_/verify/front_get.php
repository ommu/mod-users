<?php
/**
 * User Verify (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);

if(Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) {
	echo Yii::t('phrase', 'Hi, <strong>{name}</strong> an email with instructions for creating a new password has been sent to <strong>{email}</strong>', array(
		'{name}'=>Yii::app()->getRequest()->getParam('name'),
		'{email}'=>Yii::app()->getRequest()->getParam('email'),
	));

} else {?>
	<div class="form">
		<?php echo $this->renderPartial('_form', array(
			'model'=>$model,
		)); ?>
	</div>
<?php }?>