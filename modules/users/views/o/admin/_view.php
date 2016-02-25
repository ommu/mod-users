<?php
/**
 * Users (users)
 * @var $this AdminController
 * @var $data Users
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 25 February 2016, 15:47 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->user_id), array('view', 'id'=>$data->user_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_id')); ?>:</b>
	<?php echo CHtml::encode($data->source_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level_id')); ?>:</b>
	<?php echo CHtml::encode($data->level_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profile_id')); ?>:</b>
	<?php echo CHtml::encode($data->profile_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('language_id')); ?>:</b>
	<?php echo CHtml::encode($data->language_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('displayname')); ?>:</b>
	<?php echo CHtml::encode($data->displayname); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('photos')); ?>:</b>
	<?php echo CHtml::encode($data->photos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enabled')); ?>:</b>
	<?php echo CHtml::encode($data->enabled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('verified')); ?>:</b>
	<?php echo CHtml::encode($data->verified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_ip')); ?>:</b>
	<?php echo CHtml::encode($data->creation_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastlogin_date')); ?>:</b>
	<?php echo CHtml::encode($data->lastlogin_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastlogin_ip')); ?>:</b>
	<?php echo CHtml::encode($data->lastlogin_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastlogin_from')); ?>:</b>
	<?php echo CHtml::encode($data->lastlogin_from); ?>
	<br />

	*/ ?>

</div>