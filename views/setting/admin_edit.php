<?php
/**
 * User Settings (user-setting)
 * @var $this SettingController
 * @var $model UserSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 10 August 2017, 13:50 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		Yii::t('phrase', 'Update'),
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>