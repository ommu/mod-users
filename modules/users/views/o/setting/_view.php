<?php
/**
 * User Settings (user-setting)
 * @var $this SettingController
 * @var $data UserSetting
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 10 August 2017, 13:50 WIB
 * @link @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('license')); ?>:</b>
	<?php echo CHtml::encode($data->license); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('permission')); ?>:</b>
	<?php echo CHtml::encode($data->permission); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_keyword')); ?>:</b>
	<?php echo CHtml::encode($data->meta_keyword); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_description')); ?>:</b>
	<?php echo CHtml::encode($data->meta_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forgot_diff_type')); ?>:</b>
	<?php echo CHtml::encode($data->forgot_diff_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forgot_difference')); ?>:</b>
	<?php echo CHtml::encode($data->forgot_difference); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('verify_diff_type')); ?>:</b>
	<?php echo CHtml::encode($data->verify_diff_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('verify_difference')); ?>:</b>
	<?php echo CHtml::encode($data->verify_difference); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invite_diff_type')); ?>:</b>
	<?php echo CHtml::encode($data->invite_diff_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invite_difference')); ?>:</b>
	<?php echo CHtml::encode($data->invite_difference); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invite_order')); ?>:</b>
	<?php echo CHtml::encode($data->invite_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->modified_date, true)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_id')); ?>:</b>
	<?php echo CHtml::encode($data->modified->displayname); ?>
	<br />


</div>