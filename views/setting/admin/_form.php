<?php
/**
 * User Settings (user-setting)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\setting\AdminController
 * @var $model ommu\users\models\UserSetting
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 9 October 2017, 11:22 WIB
 * @modified date 8 November 2018, 12:47 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use ommu\users\models\UserSetting;
?>

<div class="user-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $this->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = UserSetting::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php $forgotDiffType = UserSetting::getForgotDiffType();
$forgot_diff_type = $form->field($model, 'forgot_diff_type', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->dropDownList($forgotDiffType, ['prompt' => ''])
	->label($model->getAttributeLabel('forgot_diff_type')); ?>

<?php echo $form->field($model, 'forgot_difference', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$forgot_diff_type.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-12 offset-sm-3']])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('forgot_difference')); ?>

<?php $verifyDiffType = UserSetting::getForgotDiffType();
$verify_diff_type = $form->field($model, 'verify_diff_type', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->dropDownList($verifyDiffType, ['prompt' => ''])
	->label($model->getAttributeLabel('verify_diff_type')); ?>

<?php echo $form->field($model, 'verify_difference', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$verify_diff_type.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-12 offset-sm-3']])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('verify_difference')); ?>

<?php $inviteDiffType = UserSetting::getForgotDiffType();
$invite_diff_type = $form->field($model, 'invite_diff_type', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6 col-6'], 'options' => ['tag' => null]])
	->dropDownList($inviteDiffType, ['prompt' => ''])
	->label($model->getAttributeLabel('invite_diff_type')); ?>

<?php echo $form->field($model, 'invite_difference', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$invite_diff_type.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-12 offset-sm-3']])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('invite_difference')); ?>

<?php $inviteOrder = UserSetting::getInviteOrder();
echo $form->field($model, 'invite_order')
	->dropDownList($inviteOrder, ['prompt' => ''])
	->label($model->getAttributeLabel('invite_order')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>