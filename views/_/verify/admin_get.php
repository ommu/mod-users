<?php
/**
 * User Verify (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
 
	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		'Create',
	);

if(Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) {?>
	<div class="users-forgot">
		<div>
			<?php echo Yii::t('phrase', 'Hi, <strong>{name}</strong> sebuah code verifikasi telah kami kirimkan ke email <strong>{email}</strong>', array(
				'{name}'=>Yii::app()->getRequest()->getParam('name'),
				'{email}=>'Yii::app()->getRequest()->getParam('email'),
			));?>
		</div>
	</div>

<?php } else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
	));
}?>