<?php
/**
 * User Settings (user-setting)
 * @var $this SettingController
 * @var $model UserSetting
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 10 August 2017, 13:50 WIB
 * @link @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'User Settings'=>array('manage'),
		$model->id,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value'=>$model->id,
		),
		array(
			'name'=>'license',
			'value'=>$model->license,
			//'value'=>$model->license ? $model->license : '-',
		),
		array(
			'name'=>'permission',
			'value'=>$model->permission,
			//'value'=>$model->permission ? $model->permission : '-',
		),
		array(
			'name'=>'meta_keyword',
			'value'=>$model->meta_keyword ? $model->meta_keyword : '-',
			//'value'=>$model->meta_keyword ? CHtml::link($model->meta_keyword, Yii::app()->request->baseUrl.'/public/visit/'.$model->meta_keyword, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'meta_description',
			'value'=>$model->meta_description ? $model->meta_description : '-',
			//'value'=>$model->meta_description ? CHtml::link($model->meta_description, Yii::app()->request->baseUrl.'/public/visit/'.$model->meta_description, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'forgot_diff_type',
			'value'=>$model->forgot_diff_type,
			//'value'=>$model->forgot_diff_type ? $model->forgot_diff_type : '-',
		),
		array(
			'name'=>'forgot_difference',
			'value'=>$model->forgot_difference,
			//'value'=>$model->forgot_difference ? $model->forgot_difference : '-',
		),
		array(
			'name'=>'verify_diff_type',
			'value'=>$model->verify_diff_type,
			//'value'=>$model->verify_diff_type ? $model->verify_diff_type : '-',
		),
		array(
			'name'=>'verify_difference',
			'value'=>$model->verify_difference,
			//'value'=>$model->verify_difference ? $model->verify_difference : '-',
		),
		array(
			'name'=>'invite_diff_type',
			'value'=>$model->invite_diff_type,
			//'value'=>$model->invite_diff_type ? $model->invite_diff_type : '-',
		),
		array(
			'name'=>'invite_difference',
			'value'=>$model->invite_difference,
			//'value'=>$model->invite_difference ? $model->invite_difference : '-',
		),
		array(
			'name'=>'invite_order',
			'value'=>$model->invite_order,
			//'value'=>$model->invite_order ? $model->invite_order : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id ? $model->modified->displayname : '-',
		),
	),
)); ?>

<div class="box">
</div>
<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>