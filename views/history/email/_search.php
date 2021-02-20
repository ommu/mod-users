<?php
/**
 * User History Emails (user-history-email)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\history\EmailController
 * @var $model ommu\users\models\search\UserHistoryEmail
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 05:36 WIB
 * @modified date 13 November 2018, 00:03 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-history-email-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php $level = UserLevel::getLevel();
		echo $form->field($model, 'userLevel')
			->dropDownList($level, ['prompt' => '']);?>

		<?php echo $form->field($model, 'email');?>

		<?php echo $form->field($model, 'update_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>