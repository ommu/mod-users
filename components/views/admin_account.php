<?php
	if($model->photo_id == 0)
		$images = Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/users/default.png', 82, 82, 1);
	else
		$images = Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/users/'.Yii::app()->user->id.'/'.$model->photo->photo, 82, 82, 1);
?>

<?php //begin.Information ?>
<div class="account">
	<?php //begin.Photo ?>
	<a off_address="" id="uplaod-image" class="photo" href="<?php echo Yii::app()->createUrl('users/o/photo/ajaxadd', array('type'=>'admin'));?>" title="<?php echo Phrase::trans(16223,1).': '.Yii::app()->user->displayname;?>"><img src="<?php echo $images;?>" alt="<?php echo $model->photo_id != 0 ? Yii::app()->user->displayname : 'Ommu Platform';?>"/></a>
	<div class="info">
		<?php echo Yii::t('phrase', 'Welcome');?>, <a href="<?php echo Yii::app()->createUrl('users/o/admin/edit')?>" title="<?php echo Phrase::trans(16222,1).': '.Yii::app()->user->displayname;?>"><?php echo Yii::app()->user->displayname;?></a>
		<span><?php echo Yii::t('phrase', 'Last sign in');?> : <?php echo date('d-m-Y', strtotime($model->lastlogin_date));?></span>
		<a class="signout" href="<?php echo Yii::app()->createUrl('site/logout');?>" title="<?php echo Yii::t('phrase', 'Logout').': '.Yii::app()->user->displayname;?>"><?php echo Yii::t('phrase', 'Logout');?></a>
	</div>
</div>
<?php //end.Information ?>
