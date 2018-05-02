<?php
/**
 * User Verifies (user-verify)
 * @var $this yii\web\View
 * @var $this app\coremodules\user\controllers\VerifyController
 * @var $model app\coremodules\user\models\search\UserVerify
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 17 October 2017, 15:00 WIB
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
		<?= $form->field($model, 'verify_id') ?>

		<?= $form->field($model, 'publish') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'code') ?>

		<?= $form->field($model, 'verify_date') ?>

		<?= $form->field($model, 'verify_ip') ?>

		<?= $form->field($model, 'expired_date') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'deleted_date') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
