<?php
/**
 * User Invites (user-invites)
 * @var $this InviteController
 * @var $model UserInvites
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 5 August 2017, 17:43 WIB
 * @modified date 24 July 2018, 09:36 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Invites'=>array('manage'),
		$model->displayname,
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
			'name'=>'invite_id',
			'value'=>$model->invite_id,
		),
		array(
			'name'=>'publish',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->invite_id)), $model->publish),
			'type'=>'raw',
		),
		array(
			'name'=>'displayname',
			'value'=>$model->displayname ? $model->displayname : '-',
		),
		array(
			'name'=>'email_search',
			'value'=>$model->newsletter->email ? $model->newsletter->email : '-',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'userlevel_search',
			'value'=>$model->user->level->title->message ? $model->user->level->title->message : '-',
		),
		array(
			'name'=>'code',
			'value'=>$model->code ? $model->code : '-',
		),
		array(
			'name'=>'invites',
			'value'=>$model->invites ? $model->invites : '-',
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
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
		),
		array(
			'name'=>'modified_search',
			'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date) : '-',
		),
		array(
			'name'=>'register_search',
			'value'=>$model->newsletter->view->register ? $this->renderPartial('_view_register', array('model'=>$model), true, false) : '-',
			'type'=>'raw',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
