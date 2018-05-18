<?php
/**
 * User History Logins (user-history-login)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\HistoryLoginController
 * @var $model ommu\users\models\search\UserHistoryLogin
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 8 October 2017, 05:39 WIB
 * @modified date 5 May 2018, 02:17 WIB
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
		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'lastlogin_date')
			->input('date');?>

		<?php echo $form->field($model, 'lastlogin_ip');?>

		<?php echo $form->field($model, 'lastlogin_from');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
