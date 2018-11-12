<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\setting\LevelController
 * @var $model ommu\users\models\UserLevel
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 4 May 2018, 09:02 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use ommu\users\models\UserLevel;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id'=>$model->level_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Message');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/admin/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Info'), 'url' => Url::to(['update', 'id'=>$model->level_id]), 'icon' => 'info'],
	['label' => Yii::t('app', 'User'), 'url' => Url::to(['user', 'id'=>$model->level_id]), 'icon' => 'users'],
	['label' => Yii::t('app', 'Message'), 'url' => Url::to(['message', 'id'=>$model->level_id]), 'icon' => 'comment'],
	['label' => Yii::t('app', 'View'), 'url' => Url::to(['view', 'id'=>$model->level_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->level_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="user-level-update-message">
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

<?php $messageAllow = UserLevel::getMessageAllow();
echo $form->field($model, 'message_allow', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'If set to "nobody", none of the other settings on this page will apply. Otherwise, users will have access to their private message inbox and will be able to send each other messages.').'</span>{input}{error}</div>'])
	->radioList($messageAllow, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('message_allow'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $messageLimit = UserLevel::getMessageLimit();
$message_limit_inbox = $form->field($model, 'message_limit[inbox]', ['template' => '<div class="col-md-3 col-sm-4 col-xs-6 col-sm-offset-3">{input}</div><div class="col-md-3 col-sm-5 col-xs-6 pt-5">'.Yii::t('app', 'conversations in inbox folder.').'</div><div class="clearfix mb-10"></div>', 'options' => ['tag' => null]])
	->dropDownList($messageLimit, ['prompt' => ''])
	->label($model->getAttributeLabel('message_limit[inbox]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $message_limit_outbox = $form->field($model, 'message_limit[outbox]', ['template' => '<div class="col-md-3 col-sm-4 col-xs-6 col-sm-offset-3">{input}</div><div class="col-md-3 col-sm-5 col-xs-6 pt-5">'.Yii::t('app', 'conversations in outbox folder.').'</div>', 'options' => ['tag' => null]])
	->dropDownList($messageLimit, ['prompt' => ''])
	->label($model->getAttributeLabel('message_limit[outbox]'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'message_limit', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px mb-10">'.Yii::t('app', 'How many total conversations will users be allowed to store in their inbox and outbox? If a user\'s inbox or outbox is full and a new conversation is started, the oldest conversation will be automatically deleted.').'</span></div>'.$message_limit_inbox.$message_limit_outbox.'<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">{error}</div>'])
	->dropDownList($messageLimit, ['prompt' => ''])
	->label($model->getAttributeLabel('message_limit'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>
</div>