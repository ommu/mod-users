<?php
/**
 * User Verifies (user-verify)
 * @var $this VerifyController
 * @var $model UserVerify
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 August 2017, 06:44 WIB
 * @modified date 24 July 2018, 08:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Verifies'=>array('manage'),
		$model->user->displayname,
	);
?>

<?php //begin.Messages ?>
<div id="ajax-message">
<?php if(Yii::app()->user->hasFlash('success'))
	echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');?>
</div>
<?php //end.Messages ?>

<div class="dialog-content">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'verify_id',
			'value'=>$model->verify_id,
		),
		array(
			'name'=>'publish',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->verify_id)), $model->publish),
			'type'=>'raw',
		),
		array(
			'name'=>'expired_search',
			'value'=>$model->view->expired == 1 ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type'=>'raw',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'code',
			'value'=>$model->code ? $model->code : '-',
		),
		array(
			'name'=>'verify_date',
			'value'=>!in_array($model->verify_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->verify_date) : '-',
		),
		array(
			'name'=>'verify_ip',
			'value'=>$model->verify_ip ? $model->verify_ip : '-',
		),
		array(
			'name'=>'expired_date',
			'value'=>!in_array($model->expired_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->expired_date) : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
		),
		array(
			'name'=>'modified_search',
			'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'deleted_date',
			'value'=>!in_array($model->deleted_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->deleted_date) : '-',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
