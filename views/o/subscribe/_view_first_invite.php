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

	$first_invite_date = $model->view->first_invite_date && !in_array($model->view->first_invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->view->first_invite_date) : '-';
	$first_invite_user = $model->view->firstUser->displayname ? $model->view->firstUser->displayname : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Date: {first_invite_date}', array('{first_invite_date}'=>$first_invite_date));?></li>
	<li><?php echo Yii::t('phrase', 'User: {first_invite_user}', array('{first_invite_user}'=>$first_invite_user));?></li>
<ul>