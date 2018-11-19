<?php
/**
 * Users (users)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\SignupController
 * @var $model ommu\users\models\UserSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 19 November 2018, 06:26 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'displayname', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('displayname'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'email', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'email'])
	->label($model->getAttributeLabel('email'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php if($setting->signup_random == 0) {
echo $form->field($model, 'password', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->passwordInput(['maxlength'=>true])
	->label($model->getAttributeLabel('password'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']);
} ?>

<?php if($setting->site_type == 1 && $setting->signup_inviteonly != 0 && $setting->signup_checkemail == 1) {
echo $form->field($model, 'invite_code_i', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->passwordInput(['maxlength'=>true])
	->label($model->getAttributeLabel('invite_code_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']);
} ?>

<div class="text-center w-100">
	<?php echo Html::submitButton(Yii::t('app', 'SIGN UP'), ['class' =>  'btn btn-success']); ?>
	<?php echo Html::submitButton(Yii::t('app', 'CANCEL'), ['class' => 'btn btn-primary']); ?>
</div>

<?php ActiveForm::end(); ?>