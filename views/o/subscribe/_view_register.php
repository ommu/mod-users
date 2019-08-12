<?php
/**
 * User Newsletter (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$register = $model->view->register == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No');
	$register_name = $model->user->displayname ? $model->user->displayname : '-';
	$register_email = $model->user->email ? $model->user->email : '-';
	$level_name = $model->user->level->title->message ? $model->user->level->title->message : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Register: {register}', array('{register}'=>$register));?></li>
	<li><?php echo Yii::t('phrase', 'Name: {register_name}', array('{register_name}'=>$register_name));?></li>
	<li><?php echo Yii::t('phrase', 'Email: {register_email}', array('{register_email}'=>$register_email));?></li>
	<li><?php echo Yii::t('phrase', 'Level: {level_name}', array('{level_name}'=>$level_name));?></li>
<ul>