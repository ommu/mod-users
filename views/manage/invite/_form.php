<?php
/**
 * User Invites (user-invites)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\manage\InviteController
 * @var $model ommu\users\models\UserInvites
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
use app\components\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-invites-form">

<?php $form = ActiveForm::begin([
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'email_i', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->label($model->getAttributeLabel('email_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php if(!Yii::$app->isSocialMedia()) {
	$level = UserLevel::getLevel();
	if(count($level) == 1) {
		$model->level_id = key($level);
		echo $form->field($model, 'level_id')->hiddenInput()->label(false);
	} else {
		echo $form->field($model, 'level_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
			->dropDownList($level, ['prompt'=>''])
			->label($model->getAttributeLabel('level_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']);
	}
} ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>