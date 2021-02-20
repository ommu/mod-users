<?php
/**
 * User Levels (user-level)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\setting\LevelController
 * @var $model ommu\users\models\search\UserLevel
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 9 November 2018, 10:32 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="user-level-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'name_i');?>

		<?php echo $form->field($model, 'desc_i');?>

		<?php echo $form->field($model, 'message_limit');?>

		<?php echo $form->field($model, 'profile_privacy');?>

		<?php echo $form->field($model, 'profile_comments');?>

		<?php echo $form->field($model, 'photo_size');?>

		<?php echo $form->field($model, 'photo_exts');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'default')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'signup')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'message_allow')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_block')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_search')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_style')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_style_sample')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_status')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_invisible')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_views')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_change')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'profile_delete')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'photo_allow')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>