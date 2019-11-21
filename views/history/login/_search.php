<?php
/**
 * User History Logins (user-history-login)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\history\LoginController
 * @var $model ommu\users\models\search\UserHistoryLogin
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 October 2017, 05:39 WIB
 * @modified date 13 November 2018, 01:16 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-history-login-search search-form">

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
			->dropDownList($level, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'email_search');?>

		<?php echo $form->field($model, 'lastlogin_date')
			->input('date');?>

		<?php echo $form->field($model, 'lastlogin_ip');?>

		<?php echo $form->field($model, 'lastlogin_from');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>