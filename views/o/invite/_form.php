<?php
/**
 * User Invites (user-invites)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\InviteController
 * @var $model ommu\users\models\UserInvites
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 08:27 WIB
 * @modified date 13 November 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\users\models\UserLevel;
?>

<div class="user-invites-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'email_i')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('email_i')); ?>

<?php if(!Yii::$app->isSocialMedia()) {
	$level = UserLevel::getLevel();
	if(count($level) == 1) {
		$model->level_id = key($level);
		echo $form->field($model, 'level_id', ['template' => '{input}', 'options'=>['tag' => null]])
			->hiddenInput();
	} else {
		echo $form->field($model, 'level_id')
			->dropDownList($level, ['prompt'=>''])
			->label($model->getAttributeLabel('level_id'));
	}
} ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>