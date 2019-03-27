<?php
/**
 * User Levels (user-level)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\setting\LevelController
 * @var $model ommu\users\models\UserLevel
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 4 May 2018, 09:02 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use ommu\users\models\UserLevel;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => Url::to(['setting/admin/index'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id'=>$model->level_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Message');
?>

<div class="user-level-update-message">
<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $messageAllow = UserLevel::getMessageAllow();
echo $form->field($model, 'message_allow', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-9 col-sm-9 col-xs-12 col-12']])
	->radioList($messageAllow)
	->label($model->getAttributeLabel('message_allow'))
	->hint(Yii::t('app', 'If set to "nobody", none of the other settings on this page will apply. Otherwise, users will have access to their private message inbox and will be able to send each other messages.')); ?>

<?php $messageLimit = UserLevel::getMessageLimit();
$message_limit_inbox = $form->field($model, 'message_limit[inbox]', ['template' => '{beginWrapper}{input}{endWrapper}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-5 col-xs-6 col-6 pt-5'], 'options' => ['tag' => null]])
	->dropDownList($messageLimit, ['prompt' => ''])
	->label($model->getAttributeLabel('message_limit[inbox]'))
	->hint(Yii::t('app', 'conversations in inbox folder.')); ?>

<?php $message_limit_outbox = $form->field($model, 'message_limit[outbox]', ['template' => '{beginWrapper}{input}{endWrapper}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-6 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-5 col-xs-6 col-6 pt-5'], 'options' => ['tag' => null]])
	->dropDownList($messageLimit, ['prompt' => ''])
	->label($model->getAttributeLabel('message_limit[outbox]'))
	->hint(Yii::t('app', 'conversations in outbox folder.')); ?>

<?php echo $form->field($model, 'message_limit', ['template' => '{label}{hint}'.$message_limit_inbox.'<div class="clearfix mb-10"></div>'.$message_limit_outbox.'{error}', 'horizontalCssClasses' => ['hint'=>'col-md-9 col-sm-9 col-xs-12 col-12', 'error'=>'col-md-9 col-sm-9 col-xs-12 col-12 col-sm-offset-3']])
	->dropDownList($messageLimit, ['prompt' => ''])
	->label($model->getAttributeLabel('message_limit'))
	->hint(Yii::t('app', 'How many total conversations will users be allowed to store in their inbox and outbox? If a user\'s inbox or outbox is full and a new conversation is started, the oldest conversation will be automatically deleted.')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-9 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>
</div>