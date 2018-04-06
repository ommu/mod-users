<?php
/**
 * User Verify (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
 
	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		'Create',
	);

if(isset($_GET['name']) && isset($_GET['email'])) {?>
	<div class="users-forgot">
		<div>
			<?php echo Yii::t('phrase', 'Hi, <strong>{name}</strong> sebuah code verifikasi telah kami kirimkan ke email <strong>{email}</strong>', array(
				'{name}'=>$_GET['name'],
				'{email}=>'$_GET['email'],
			));?>
		</div>
	</div>

<?php } else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
	));
}?>