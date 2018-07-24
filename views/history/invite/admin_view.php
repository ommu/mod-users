<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this InviteController
 * @var $model UserInviteHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 24 July 2018, 06:41 WIB
 * @modified date 24 July 2018, 06:41 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Invite Histories'=>array('manage'),
		$model->invite->displayname,
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
			'name'=>'invite_search',
			'value'=>$model->invite->displayname ? $model->invite->displayname : '-',
		),
		array(
			'name'=>'code',
			'value'=>$model->code ? $model->code : '-',
		),
		array(
			'name'=>'invite_date',
			'value'=>!in_array($model->invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->invite_date) : '-',
		),
		array(
			'name'=>'invite_ip',
			'value'=>$model->invite_ip ? $model->invite_ip : '-',
		),
		array(
			'name'=>'expired_date',
			'value'=>!in_array($model->expired_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->expired_date) : '-',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
