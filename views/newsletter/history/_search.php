<?php
/**
 * User Newsletter Histories (user-newsletter-history)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\newsletter\HistoryController
 * @var $model ommu\users\models\search\UserNewsletterHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:29 WIB
 * @modified date 13 November 2018, 23:44 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-newsletter-history-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'email_search');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php $level = UserLevel::getLevel();
		echo $form->field($model, 'userLevel')
			->dropDownList($level, ['prompt' => '']);?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'updated_ip');?>

		<?php echo $form->field($model, 'status')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'register_search')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>