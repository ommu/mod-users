<?php
/**
 * User Newsletter Histories (user-newsletter-history)
 * @var $this SubscribeController
 * @var $model UserNewsletterHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 24 July 2018, 06:42 WIB
 * @modified date 24 July 2018, 06:42 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'User Newsletter Histories'=>array('manage'),
		$model->newsletter->user->displayname,
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
			'name'=>'status',
			'value'=>$model->status ? Yii::t('phrase', 'Subscribe') : Yii::t('phrase', 'Unsubscribe'),
			'type'=>'raw',
		),
		array(
			'name'=>'newsletter_search',
			'value'=>$model->newsletter->user->displayname ? $model->newsletter->user->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date) : '-',
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
