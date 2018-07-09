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
 * @link https://github.com/ommu/mod-users
 *
 */

	$register = $model->newsletter->view->register == 1 ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No');
	$register_date = !in_array($model->newsletter->view->register_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->newsletter->view->register_date, true) : '-';
	$register_name = $model->newsletter->user->displayname ? $model->newsletter->user->displayname : '-';
	$level_name = $model->newsletter->view->user_id ? $model->newsletter->user->level->title->message : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Register: $register', array('$register'=>$register));?></li>
	<li><?php echo Yii::t('phrase', 'Date: $register_date', array('$register_date'=>$register_date));?></li>
	<li><?php echo Yii::t('phrase', 'Name: $register_name', array('$register_name'=>$register_name));?></li>
	<li><?php echo Yii::t('phrase', 'Level: $level_name', array('$level_name'=>$level_name));?></li>
<ul>