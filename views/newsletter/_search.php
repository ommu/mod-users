<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\NewsletterController
 * @var $model ommu\users\models\search\UserNewsletter
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 15:59 WIB
 * @link https://github.com/ommu/mod-users
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
		<?php echo $form->field($model, 'status')
			->checkbox();?>

		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'reference_search');?>

		<?php echo $form->field($model, 'email');?>

		<?php echo $form->field($model, 'subscribe_date')
			->input('date');?>

		<?php echo $form->field($model, 'subscribe_id');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'updated_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
