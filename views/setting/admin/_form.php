<?php
/**
 * User Settings (user-setting)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\setting\AdminController
 * @var $model ommu\users\models\UserSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 9 October 2017, 11:22 WIB
 * @modified date 8 November 2018, 12:47 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\users\models\UserSetting;
?>

<div class="user-setting-form">

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

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $this->licenseCode();
echo $form->field($model, 'license', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px mb-10">'.Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'</span>{input}{error}<span class="small-px">'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX').'</span></div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $permission = UserSetting::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px mb-10">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission, ['class'=>'desc mt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('permission'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_description', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('meta_description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_keyword', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2, 'rows'=>6])
	->label($model->getAttributeLabel('meta_keyword'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $forgotDiffType = UserSetting::getForgotDiffType();
$forgot_diff_type = $form->field($model, 'forgot_diff_type', ['template' => '<div class="col-md-3 col-sm-5 col-xs-6">{input}</div>', 'options' => ['tag' => null]])
	->dropDownList($forgotDiffType, ['prompt' => ''])
	->label($model->getAttributeLabel('forgot_diff_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'forgot_difference', ['template' => '{label}<div class="col-md-3 col-sm-4 col-xs-6">{input}</div>'.$forgot_diff_type.'<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('forgot_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $verifyDiffType = UserSetting::getForgotDiffType();
$verify_diff_type = $form->field($model, 'verify_diff_type', ['template' => '<div class="col-md-3 col-sm-5 col-xs-6">{input}</div>', 'options' => ['tag' => null]])
	->dropDownList($verifyDiffType, ['prompt' => ''])
	->label($model->getAttributeLabel('verify_diff_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'verify_difference', ['template' => '{label}<div class="col-md-3 col-sm-4 col-xs-6">{input}</div>'.$verify_diff_type.'<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('verify_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $inviteDiffType = UserSetting::getForgotDiffType();
$invite_diff_type = $form->field($model, 'invite_diff_type', ['template' => '<div class="col-md-3 col-sm-5 col-xs-6">{input}</div>', 'options' => ['tag' => null]])
	->dropDownList($inviteDiffType, ['prompt' => ''])
	->label($model->getAttributeLabel('invite_diff_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'invite_difference', ['template' => '{label}<div class="col-md-3 col-sm-4 col-xs-6">{input}</div>'.$invite_diff_type.'<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('invite_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $inviteOrder = UserSetting::getInviteOrder();
echo $form->field($model, 'invite_order', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($inviteOrder, ['prompt' => ''])
	->label($model->getAttributeLabel('invite_order'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>