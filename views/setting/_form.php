<?php
/**
 * User Settings (user-setting)
 * @var $this yii\web\View
 * @var $this app\modules\user\controllers\SettingController
 * @var $model app\modules\user\models\UserSetting
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 9 October 2017, 11:22 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\models\UserSetting;
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
if($model->isNewRecord || (!$model->isNewRecord && $model->license == ''))
	$model->license = UserSetting::getLicense();
echo $form->field($model, 'license', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php 
$permission = [
	1 => Yii::t('app', 'Yes, the public can view user unless they are made private.'),
	0 => Yii::t('app', 'No, the public cannot view user.'),
];
echo $form->field($model, 'permission', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12"><span class="small-px">'.Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.').'</span>{input}{error}</div>'])
	->radioList($permission, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('permission'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_description', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_description'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'meta_keyword', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('meta_keyword'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="form-group field-usersetting-forgot_difference">
	<?php echo $form->field($model, 'forgot_difference', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('forgot_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php echo $form->field($model, 'forgot_difference', ['template' => '{input}{error}'])
			->textInput(['type' => 'number'])
			->label($model->getAttributeLabel('forgot_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php 
		$forgot_diff_type = [
			0 => Yii::t('app', 'Day'),
			1 => Yii::t('app', 'Hour'),
		];
		echo $form->field($model, 'forgot_diff_type', ['template' => '{input}'])
			->dropDownList($forgot_diff_type, ['prompt' => ''])
			->label($model->getAttributeLabel('forgot_diff_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<div class="form-group field-usersetting-verify_difference">
	<?php echo $form->field($model, 'verify_difference', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('verify_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php echo $form->field($model, 'verify_difference', ['template' => '{input}{error}'])
			->textInput(['type' => 'number'])
			->label($model->getAttributeLabel('verify_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php 
		$verify_diff_type = [
			0 => Yii::t('app', 'Day'),
			1 => Yii::t('app', 'Hour'),
		];
		echo $form->field($model, 'verify_diff_type', ['template' => '{input}'])
			->dropDownList($verify_diff_type, ['prompt' => ''])
			->label($model->getAttributeLabel('verify_diff_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<div class="form-group field-usersetting-invite_difference">
	<?php echo $form->field($model, 'invite_difference', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('invite_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php echo $form->field($model, 'invite_difference', ['template' => '{input}{error}'])
			->textInput(['type' => 'number'])
			->label($model->getAttributeLabel('invite_difference'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<?php 
		$invite_diff_type = [
			0 => Yii::t('app', 'Day'),
			1 => Yii::t('app', 'Hour'),
		];
		echo $form->field($model, 'invite_diff_type', ['template' => '{input}'])
			->dropDownList($invite_diff_type, ['prompt' => ''])
			->label($model->getAttributeLabel('invite_diff_type'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>
	</div>
</div>

<?php 
$invite_order = [
	'asc' => Yii::t('app', 'Ascending'),
	'desc' => Yii::t('app', 'Descending'),
];
echo $form->field($model, 'invite_order', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->dropDownList($invite_order, ['prompt' => ''])
	->label($model->getAttributeLabel('invite_order'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>