<?php
/**
 * User Verify (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		'Create',
	);

if(isset($_GET['name']) && isset($_GET['email'])) {?>
	<div class="users-forgot">
		<div>
			<?php echo Phrase::trans(16235,1, array(
				$_GET['name'],
				$_GET['email'],
			));?>
		</div>
	</div>

<?php } else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
	));
}?>