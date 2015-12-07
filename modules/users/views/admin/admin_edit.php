<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Users'=>array('manage'),
		$model->user_id=>array('view','id'=>$model->user_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>