<?php
/**
 * Users (users)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\manage\PersonalController
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
use app\models\CoreLanguages;

$module = strtolower(Yii::$app->controller->module->id);
$controller = strtolower(Yii::$app->controller->id);
$action = strtolower(Yii::$app->controller->action->id);
echo $module.'<br/>';
echo $controller.'<br/>';
echo $action.'<br/>';
?>

<div class="users-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $level = UserLevel::getLevel();
echo $form->field($model, 'level_id')
	->dropDownList($level, ['prompt'=>''])
	->label($model->getAttributeLabel('level_id')); ?>

<?php $language = CoreLanguages::getLanguage();
echo $form->field($model, 'language_id')
	->dropDownList($language, ['prompt'=>''])
	->label($model->getAttributeLabel('language_id')); ?>

<?php echo $form->field($model, 'email')
	->textInput(['type'=>'email'])
	->label($model->getAttributeLabel('email')); ?>

<?php echo $form->field($model, 'displayname')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('displayname')); ?>

<?php echo $form->field($model, 'password')
	->passwordInput(['maxlength'=>true])
	->label($model->getAttributeLabel('password')); ?>

<?php echo $form->field($model, 'salt')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('salt')); ?>

<?php echo $form->field($model, 'creation_ip')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('creation_ip')); ?>

<?php echo $form->field($model, 'modified_date')
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('modified_date')); ?>

<?php echo $form->field($model, 'lastlogin_date')
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('lastlogin_date')); ?>

<?php echo $form->field($model, 'lastlogin_ip')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('lastlogin_ip')); ?>

<?php echo $form->field($model, 'lastlogin_from')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('lastlogin_from')); ?>

<?php echo $form->field($model, 'update_date')
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('update_date')); ?>

<?php echo $form->field($model, 'update_ip')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('update_ip')); ?>

<?php $enabled = Users::getEnabled();
echo $form->field($model, 'enabled')
	->dropDownList($enabled, ['prompt'=>''])
	->label($model->getAttributeLabel('enabled')); ?>

<?php echo $form->field($model, 'verified')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('verified')); ?>

<?php echo $form->field($model, 'deactivate')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('deactivate')); ?>

<?php echo $form->field($model, 'search')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('search')); ?>

<?php echo $form->field($model, 'invisible')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('invisible')); ?>

<?php echo $form->field($model, 'privacy')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('privacy')); ?>

<?php echo $form->field($model, 'comments')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('comments')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>