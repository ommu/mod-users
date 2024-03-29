<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $model Users
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$this->breadcrumbs=array(
		'Users'=>array('manage'),
		$model->user_id=>array('view','id'=>$model->user_id),
		'Photo',
	);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
		'on_post' => '',
	),
)); ?>
<div class="dialog-content">
	<fieldset>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'photos', array('class'=>'col-form-label col-lg-3 col-md-3 col-sm-12')); ?>
			<div class="col-lg-6 col-md-9 col-sm-12">
				<?php 
				if(!$model->getErrors())
					$model->old_photos_i = $model->photos;
				if(!$model->isNewRecord && $model->old_photos_i != '') {
					echo $form->hiddenField($model,'old_photos_i');
					$photo = Yii::app()->request->baseUrl.'/public/users/'.$model->user_id.'/'.$model->old_photos_i;?>
						<img class="mb-10" src="<?php echo Utility::getTimThumb($photo, 300, 300, 3);?>" alt="">
				<?php }?>
				<?php echo $form->fileField($model,'photos', array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'photos'); ?>
				<div class="small-px">extensions are allowed: <?php echo Utility::formatFileType($photo_exts, false);?></div>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton(Yii::t('phrase', 'Upload'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


