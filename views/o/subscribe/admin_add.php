<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 09:37 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Newsletters'=>array('manage'),
		Yii::t('phrase', 'Create'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
