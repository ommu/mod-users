<?php
/**
 * User Newsletter (user-newsletter)
 * @var $this NewsletterController
 * @var $model UserNewsletter
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 7 August 2017, 10:11 WIB
 * @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Support Newsletters'=>array('manage'),
		$model->newsletter_id=>array('view','id'=>$model->newsletter_id),
		'View',
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'newsletter_id',
			array(
				'name'=>'status',
				'value'=>$model->status == 1 ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type' => 'raw',
			),
			array(
				'name'=>'user_id',
				'value'=>$model->user->displayname ? $model->user->displayname : '-',
			),
			array(
				'name'=>'email',
				'value'=>$model->email,
			),
			array(
				'name'=>'subscribe_date',
				'value'=>!in_array($model->subscribe_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->subscribe_date, true) : '-',
			),
			array(
				'name'=>'subscribe_id',
				'value'=>$model->subscribe->displayname ? $model->subscribe->displayname : '-',
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
			array(
				'name'=>'updated_ip',
				'value'=>$model->updated_ip ? $model->updated_ip : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>