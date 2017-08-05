<?php
/**
 * User Invite Queue (user-invite-queue)
 * @var $this InviteController
 * @var $model UserInviteQueue
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 5 August 2017, 17:43 WIB
 * @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'User Invite Queues'=>array('manage'),
		$model->queue_id=>array('view','id'=>$model->queue_id),
		'View',
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'queue_id',
			array(
				'name'=>'publish',
				'value'=>$model->publish == 1 ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type' => 'raw',
			),
			array(
				'name'=>'displayname',
				'value'=>$model->displayname ? $model->displayname : '-',
			),
			array(
				'name'=>'email',
				'value'=>$model->email,
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'creation_id',
				'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'register_search',
				'value'=>$model->view->register == 1 ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type' => 'raw',
			),
			array(
				'name'=>'register_name_i',
				'value'=>$model->view->user_id ? $model->view->user->displayname : '-',
			),
			array(
				'name'=>'register_date_i',
				'value'=>!in_array($model->view->register_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->view->register_date, true) : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>