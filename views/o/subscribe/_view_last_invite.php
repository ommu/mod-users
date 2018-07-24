<?php
/**
 * User Newsletter (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */

	$last_invite_date = $model->view->last_invite_date && !in_array($model->view->last_invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->view->last_invite_date) : '-';
	$last_invite_user = $model->view->lastUser->displayname ? $model->view->lastUser->displayname : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Date: {last_invite_date}', array('{last_invite_date}'=>$last_invite_date));?></li>
	<li><?php echo Yii::t('phrase', 'User: {last_invite_user}', array('{last_invite_user}'=>$last_invite_user));?></li>
<ul>