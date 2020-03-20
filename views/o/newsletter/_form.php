<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\NewsletterController
 * @var $model ommu\users\models\UserNewsletter
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 14 November 2018, 01:24 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="user-newsletter-form">

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

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>