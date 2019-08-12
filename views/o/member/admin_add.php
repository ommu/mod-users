<?php
/**
 * Users (users)
 * @var $this MemberController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'Users'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'setting'=>$setting,
)); ?>