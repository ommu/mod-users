<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this yii\web\View
 * @var $this app\coremodules\user\controllers\SubscribeController
 * @var $model app\coremodules\user\models\search\UserNewsletter
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 23 October 2017, 08:28 WIB
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
		<?= $form->field($model, 'newsletter_id') ?>

		<?= $form->field($model, 'status') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'reference_id') ?>

		<?= $form->field($model, 'email') ?>

		<?= $form->field($model, 'subscribe_date') ?>

		<?= $form->field($model, 'subscribe_id') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'updated_date') ?>

		<?= $form->field($model, 'updated_ip') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
