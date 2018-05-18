<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\LevelController
 * @var $model ommu\users\models\search\UserLevel
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 4 May 2018, 09:02 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?php echo $form->field($model, 'name_i');?>

		<?php echo $form->field($model, 'desc_i');?>

		<?php echo $form->field($model, 'default')
			->checkbox();?>

		<?php echo $form->field($model, 'signup')
			->checkbox();?>

		<?php echo $form->field($model, 'message_allow')
			->checkbox();?>

		<?php echo $form->field($model, 'message_limit');?>

		<?php echo $form->field($model, 'profile_block')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_search')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_privacy');?>

		<?php echo $form->field($model, 'profile_comments');?>

		<?php echo $form->field($model, 'profile_style')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_style_sample')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_status')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_invisible')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_views')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_change')
			->checkbox();?>

		<?php echo $form->field($model, 'profile_delete')
			->checkbox();?>

		<?php echo $form->field($model, 'photo_allow')
			->checkbox();?>

		<?php echo $form->field($model, 'photo_size');?>

		<?php echo $form->field($model, 'photo_exts');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_search');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'slug');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
