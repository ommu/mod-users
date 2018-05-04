<?php
/**
 * User History Logins (user-history-login)
 * @var $this yii\web\View
 * @var $this app\modules\user\controllers\history\HistoryLoginController
 * @var $model app\modules\user\models\search\UserHistoryLogin
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 05:39 WIB
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
		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'lastlogin_date') ?>

		<?= $form->field($model, 'lastlogin_ip') ?>

		<?= $form->field($model, 'lastlogin_from') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
