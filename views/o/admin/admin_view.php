<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $model Users
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 15 September 2018, 19:57 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'Users'=>array('manage'),
		$model->displayname,
	);
?>

<?php //begin.Messages ?>
<div id="ajax-message">
<?php if(Yii::app()->user->hasFlash('success'))
	echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');?>
</div>
<?php //end.Messages ?>

<div class="box">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'user_id',
			'value'=>$model->user_id,
		),
		array(
			'name'=>'enabled',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('enabled', array('id'=>$model->user_id)), $model->enabled, Users::getEnabled()),
			'type'=>'raw',
		),
		array(
			'name'=>'verified',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('verified', array('id'=>$model->user_id)), $model->verified, 'Verified,Unverified'),
			'type'=>'raw',
		),
		array(
			'name'=>'level_id',
			'value'=>$model->level->title->message ? $model->level->title->message : '-',
		),
		array(
			'name'=>'language_id',
			'value'=>$model->language->name ? $model->language->name : '-',
		),
		array(
			'name'=>'email',
			'value'=>$model->email ? $model->email : '-',
		),
		array(
			'name'=>'username',
			'value'=>$model->username ? $model->username : '-',
		),
		array(
			'name'=>'first_name',
			'value'=>$model->first_name ? $model->first_name : '-',
		),
		array(
			'name'=>'last_name',
			'value'=>$model->last_name ? $model->last_name : '-',
		),
		array(
			'name'=>'displayname',
			'value'=>$model->displayname ? $model->displayname : '-',
		),
		/*
		array(
			'name'=>'password',
			'value'=>$model->password ? $model->password : '-',
		),
		*/
		array(
			'name'=>'photos',
			'value'=>$model->photos ? CHtml::link($model->photos, join('/', array(Yii::app()->request->baseUrl, Users::getUploadPath(false), $model->user_id, $model->photos), array('target'=>'_blank'))) : '-',
			'type'=>'raw',
		),
		/*
		array(
			'name'=>'salt',
			'value'=>$model->salt ? $model->salt : '-',
		),
		*/
		array(
			'name'=>'deactivate',
			'value'=>$this->parseYesNo($model->deactivate),
			'type'=>'raw',
		),
		array(
			'name'=>'search',
			'value'=>$this->parseYesNo($model->search),
			'type'=>'raw',
		),
		array(
			'name'=>'invisible',
			'value'=>$this->parseYesNo($model->invisible),
			'type'=>'raw',
		),
		array(
			'name'=>'privacy',
			'value'=>$this->parseYesNo($model->privacy),
			'type'=>'raw',
		),
		array(
			'name'=>'comments',
			'value'=>$this->parseYesNo($model->comments),
			'type'=>'raw',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
		),
		array(
			'name'=>'creation_ip',
			'value'=>$model->creation_ip ? $model->creation_ip : '-',
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
		array(
			'name'=>'update_date',
			'value'=>!in_array($model->update_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->update_date) : '-',
		),
		array(
			'name'=>'update_ip',
			'value'=>$model->update_ip ? $model->update_ip : '-',
		),
	),
)); ?>
</div>
