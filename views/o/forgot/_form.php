<?php
/**
 * User Forgots (user-forgot)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\ForgotController
 * @var $model ommu\users\models\UserForgot
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 17 October 2017, 15:01 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="user-forgot-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
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
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('email_i')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>