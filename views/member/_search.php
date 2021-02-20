<?php
/**
 * Users (users)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\MemberController
 * @var $model ommu\users\models\search\Users
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\Users;
use ommu\users\models\UserLevel;
use app\models\CoreLanguages;
?>

<div class="users-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $level = UserLevel::getLevel();
		echo $form->field($model, 'level_id')
			->dropDownList($level, ['prompt' => '']);?>

		<?php $language = CoreLanguages::getLanguage();
		echo $form->field($model, 'language_id')
			->dropDownList($language, ['prompt' => '']);?>

		<?php echo $form->field($model, 'email');?>

		<?php echo $form->field($model, 'displayname');?>

		<?php echo $form->field($model, 'password');?>

		<?php echo $form->field($model, 'salt');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_ip');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'lastlogin_date')
			->input('date');?>

		<?php echo $form->field($model, 'lastlogin_ip');?>

		<?php echo $form->field($model, 'lastlogin_from');?>

		<?php echo $form->field($model, 'update_date')
			->input('date');?>

		<?php echo $form->field($model, 'update_ip');?>

		<?php echo $form->field($model, 'auth_key');?>

		<?php echo $form->field($model, 'jwt_claims');?>

		<?php $enabled = Users::getEnabled();
			echo $form->field($model, 'enabled')
			->dropDownList($enabled, ['prompt' => '']);?>

		<?php echo $form->field($model, 'verified')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'deactivate')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'search')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'invisible')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'privacy')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'comments')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>