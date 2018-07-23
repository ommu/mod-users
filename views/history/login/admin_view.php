<?php
/**
 * User History Logins (user-history-login)
 * @var $this LoginController
 * @var $model UserHistoryLogin
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 July 2018, 22:51 WIB
 * @modified date 23 July 2018, 22:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User History Logins'=>array('manage'),
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
			'name'=>'id',
			'value'=>$model->id,
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'lastlogin_date',
			'value'=>!in_array($model->lastlogin_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->lastlogin_date) : '-',
		),
		array(
			'name'=>'lastlogin_ip',
			'value'=>$model->lastlogin_ip ? $model->lastlogin_ip : '-',
		),
		array(
			'name'=>'lastlogin_from',
			'value'=>$model->lastlogin_from ? $model->lastlogin_from : '-',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
