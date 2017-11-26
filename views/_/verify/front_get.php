<?php
/**
 * User Verify (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-users
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		'Create',
	);

if(isset($_GET['name']) && isset($_GET['email'])) {
	echo Yii::t('phrase', 'Hi, <strong>{name}</strong> an email with instructions for creating a new password has been sent to <strong>{email}</strong>', array(
		'{name}'=>$_GET['name'],
		'{email}'=>$_GET['email'],
	));

} else {?>
	<div class="form">
		<?php echo $this->renderPartial('_form', array(
			'model'=>$model,
		)); ?>
	</div>
<?php }?>