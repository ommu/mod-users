<?php
/**
 * User Levels (user-level)
 * @var $this LevelController
 * @var $model UserLevel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 August 2017, 18:22 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Levels'=>array('manage'),
		$model->title->message=>array('view','id'=>$model->level_id),
		'View',
	);
?>

<div class="box">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'level_id',
				'value'=>$model->level_id,
			),
			array(
				'name'=>'default',
				'value'=>$model->default == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'signup',
				'value'=>$model->signup == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'name_i',
				'value'=>$model->name ? $model->title->message : '-',
			),
			array(
				'name'=>'desc_i',
				'value'=>$model->desc ? $model->description->message : '-',
			),
			array(
				'name'=>'message_allow',
				'value'=>$model->message_allow == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'message_limit',
				'value'=>$model->message_limit ? serialize($model->message_limit) : '-',
			),
			array(
				'name'=>'profile_block',
				'value'=>$model->profile_block == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_search',
				'value'=>$model->profile_search == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_privacy',
				'value'=>$model->profile_privacy ? serialize($model->profile_privacy) : '-',
			),
			array(
				'name'=>'profile_comments',
				'value'=>$model->profile_comments ? serialize($model->profile_comments) : '-',
			),
			array(
				'name'=>'profile_style',
				'value'=>$model->profile_style == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_style_sample',
				'value'=>$model->profile_style_sample == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_status',
				'value'=>$model->profile_status == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_invisible',
				'value'=>$model->profile_invisible == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_views',
				'value'=>$model->profile_views == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'profile_change',
				'value'=>$model->profile_change == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'photo_allow',
				'value'=>$model->photo_allow == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'photo_size',
				'value'=>$model->photo_size ? serialize($model->photo_size) : '-',
			),
			array(
				'name'=>'photo_exts',
				'value'=>$model->photo_exts ? serialize($model->photo_exts) : '-',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
			),
			array(
				'name'=>'creation_search',
				'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
			),
			array(
				'name'=>'modified_search',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
		),
	)); ?>
</div>