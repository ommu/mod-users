<?php
/**
 * User History Passwords (user-history-password)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\history\PasswordController
 * @var $model ommu\users\models\search\UserHistoryPassword
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 05:39 WIB
 * @modified date 13 November 2018, 01:17 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-history-password-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'user_search');?>

		<?php $level = UserLevel::getLevel();
		echo $form->field($model, 'level_search')
			->dropDownList($level, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'email_search');?>

		<?php echo $form->field($model, 'password');?>

		<?php echo $form->field($model, 'update_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>