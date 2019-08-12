<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 August 2017, 10:11 WIB
 * @modified date 24 July 2018, 09:37 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Newsletters'=>array('manage'),
		$model->email,
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
			'name'=>'newsletter_id',
			'value'=>$model->newsletter_id,
		),
		array(
			'name'=>'status',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('status', array('id'=>$model->newsletter_id)), $model->status, 'Subscribe,Unsubscribe'),
			'type'=>'raw',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'reference_search',
			'value'=>$model->reference->displayname ? $model->reference->displayname : '-',
		),
		array(
			'name'=>'email',
			'value'=>$model->email ? $model->email : '-',
		),
		array(
			'name'=>'subscribe_date',
			'value'=>!in_array($model->subscribe_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->subscribe_date) : '-',
		),
		array(
			'name'=>'subscribe_search',
			'value'=>$model->subscribe->displayname ? $model->subscribe->displayname : '-',
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
			'name'=>'updated_ip',
			'value'=>$model->updated_ip ? $model->updated_ip : '-',
		),
		array(
			'name'=>'invite_search',
			'value'=>$model->view->invites ? $model->view->invites : 0,
		),
		array(
			'name'=>'invite_user_search',
			'value'=>$model->view->invite_users ? $model->view->invite_users : 0,
		),
		array(
			'name'=>'first_invite_i',
			'value'=>$model->view->first_invite_date || $model->view->first_invite_user_id ? $this->renderPartial('_view_first_invite', array('model'=>$model), true, false) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'last_invite_i',
			'value'=>$model->view->last_invite_date || $model->view->last_invite_user_id ? $this->renderPartial('_view_last_invite', array('model'=>$model), true, false) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'register_search',
			'value'=>$model->view->register ? $this->renderPartial('_view_register', array('model'=>$model), true, false) : '-',
			'type'=>'raw',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
