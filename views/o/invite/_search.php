<?php
/**
 * User Invites (user-invites)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\InviteController
 * @var $model ommu\users\models\search\UserInvites
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 08:27 WIB
 * @modified date 13 November 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-invites-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'displayname');?>

		<?php echo $form->field($model, 'email_search');?>

		<?php echo $form->field($model, 'code');?>

		<?php echo $form->field($model, 'invites');?>

		<?php echo $form->field($model, 'inviter_search');?>

		<?php $level = UserLevel::getLevel();
		echo $form->field($model, 'level_search')
			->dropDownList($level, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'invite_date')
			->input('date');?>

		<?php echo $form->field($model, 'invite_ip');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($this->filterYesNo(), ['prompt'=>'']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>