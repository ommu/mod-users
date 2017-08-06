<?php
/**
 * User Forgots (user-forgot)
 * @var $this ForgotController
 * @var $data UserForgot
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 7 August 2017, 06:43 WIB
 * @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('forgot_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->forgot_id), array('view', 'id'=>$data->forgot_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publish')); ?>:</b>
	<?php echo CHtml::encode($data->publish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user->column_name_relation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forgot_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->forgot_date, true)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forgot_ip')); ?>:</b>
	<?php echo CHtml::encode($data->forgot_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('expired_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->expired_date, true)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->modified_date, true)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_id')); ?>:</b>
	<?php echo CHtml::encode($data->modified->displayname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deleted_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->deleted_date, true)); ?>
	<br />


</div>