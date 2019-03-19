<?php
/**
 * Users (users)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\manage\MemberController
 * @var $model ommu\users\models\Users
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use ommu\users\models\Users;
use ommu\users\models\UserLevel;
?>

<div class="users-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
$controller = strtolower(Yii::$app->controller->id);
$level = UserLevel::getLevel($controller == 'manage/admin' ? 'admin' : 'member');
if(count($level) == 1) {
	$model->level_id = key($level);
	echo $form->field($model, 'level_id')->hiddenInput()->label(false);
} else {
	echo $form->field($model, 'level_id')
		->dropDownList($level, ['prompt'=>''])
		->label($model->getAttributeLabel('level_id'));
} ?>

<?php echo $form->field($model, 'email')
	->textInput(['type'=>'email'])
	->label($model->getAttributeLabel('email')); ?>

<?php echo $form->field($model, 'displayname')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('displayname')); ?>

<?php if(($model->isNewRecord && $setting->signup_random == 0) || !$model->isNewRecord) {
if(!$model->isNewRecord && !$model->getErrors())
	$model->password = '';
echo $form->field($model, 'password')
	->passwordInput(['maxlength'=>true])
	->label($model->getAttributeLabel('password'));
} ?>

<?php if(!$model->isNewRecord) {
echo $form->field($model, 'confirmPassword')
	->passwordInput(['maxlength'=>true])
	->label($model->getAttributeLabel('confirmPassword'));
} ?>

<?php if(($model->isNewRecord && $setting->signup_approve == 0) || !$model->isNewRecord) {
$enabled = Users::getEnabled();
echo $form->field($model, 'enabled')
	->dropDownList($enabled, ['prompt'=>''])
	->label($model->getAttributeLabel('enabled'));
} ?>

<?php if(($model->isNewRecord && $setting->signup_verifyemail == 1) || !$model->isNewRecord) {
echo $form->field($model, 'verified')
	->checkbox()
	->label($model->getAttributeLabel('verified'));
} ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>