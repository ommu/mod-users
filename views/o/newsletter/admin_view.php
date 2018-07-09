<?php
/**
 * User Newsletter (user-newsletter)
 * @var $this NewsletterController
 * @var $model UserNewsletter
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 7 August 2017, 10:11 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'Support Newsletters'=>array('manage'),
		$model->newsletter_id=>array('view','id'=>$model->newsletter_id),
		'View',
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'newsletter_id',
			array(
				'name'=>'status',
				'value'=>$model->status == 1 ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type' => 'raw',
			),
			array(
				'name'=>'email',
				'value'=>$model->email,
			),
			array(
				'name'=>'subscribe_date',
				'value'=>!in_array($model->subscribe_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->subscribe_date, true) : '-',
			),
			array(
				'name'=>'subscribe_id',
				'value'=>$model->subscribe->displayname ? $model->subscribe->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date, true) : '-',
			),
			array(
				'name'=>'invite_search',
				'value'=>$model->view->invites ? $model->view->invites : 0,
			),
			array(
				'name'=>'invite_users_i',
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
			array(
				'name'=>'reference',
				'value'=>$model->reference->displayname ? $model->reference->displayname : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>