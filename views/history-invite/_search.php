<?php
/**
 * User Invite Histories (user-invite-history)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\HistoryInviteController
 * @var $model ommu\users\models\search\UserInviteHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 09:01 WIB
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
		<?php echo $form->field($model, 'invite_search');?>

		<?php echo $form->field($model, 'code');?>

		<?php echo $form->field($model, 'invite_date')
			->input('date');?>

		<?php echo $form->field($model, 'invite_ip');?>

		<?php echo $form->field($model, 'expired_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
