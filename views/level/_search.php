<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this app\modules\user\controllers\LevelController
 * @var $model app\modules\user\models\search\UserLevel
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 07:46 WIB
 * @contact (+62)856-299-4114
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
		<?= $form->field($model, 'level_id') ?>

		<?= $form->field($model, 'name') ?>

		<?= $form->field($model, 'desc') ?>

		<?= $form->field($model, 'default') ?>

		<?= $form->field($model, 'signup') ?>

		<?= $form->field($model, 'message_allow') ?>

		<?= $form->field($model, 'message_limit') ?>

		<?= $form->field($model, 'profile_block') ?>

		<?= $form->field($model, 'profile_search') ?>

		<?= $form->field($model, 'profile_privacy') ?>

		<?= $form->field($model, 'profile_comments') ?>

		<?= $form->field($model, 'profile_style') ?>

		<?= $form->field($model, 'profile_style_sample') ?>

		<?= $form->field($model, 'profile_status') ?>

		<?= $form->field($model, 'profile_invisible') ?>

		<?= $form->field($model, 'profile_views') ?>

		<?= $form->field($model, 'profile_change') ?>

		<?= $form->field($model, 'profile_delete') ?>

		<?= $form->field($model, 'photo_allow') ?>

		<?= $form->field($model, 'photo_size') ?>

		<?= $form->field($model, 'photo_exts') ?>

		<?= $form->field($model, 'creation_date') ?>

		<?= $form->field($model, 'creation_id') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'slug') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
