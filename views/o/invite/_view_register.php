<?php
/**
 * User Invites (user-invites)
 * @var $this InviteController
 * @var $model UserInvites
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 5 August 2017, 17:43 WIB
 * @modified date 24 July 2018, 09:36 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

	$register = $model->newsletter->view->register == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No');
	$register_name = $model->newsletter->user->displayname ? $model->newsletter->user->displayname : '-';
	$register_email = $model->newsletter->email ? $model->newsletter->email : '-';
	$level_name = $model->newsletter->user->level->title->message ? $model->newsletter->user->level->title->message : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Register: {register}', array('{register}'=>$register));?></li>
	<li><?php echo Yii::t('phrase', 'Name: {register_name}', array('{register_name}'=>$register_name));?></li>
	<li><?php echo Yii::t('phrase', 'Name: {register_email}', array('{register_email}'=>$register_email));?></li>
	<li><?php echo Yii::t('phrase', 'Level: {level_name}', array('{level_name}'=>$level_name));?></li>
<ul>