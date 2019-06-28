<?php
/**
 * User Levels (user-level)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\setting\LevelController
 * @var $model ommu\users\models\UserLevel
 * @var $form yii\widgets\ActiveForm
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
use app\components\widgets\ActiveForm;
use ommu\users\models\UserLevel;
use ommu\users\models\Assignments;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => Url::to(['setting/admin/index'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id'=>$model->level_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'User');
?>

<div class="user-level-update-user">

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

<?php $profileBlock = UserLevel::getProfileBlock();
echo $form->field($model, 'profile_block', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
->radioList($profileBlock)
	->label($model->getAttributeLabel('profile_block'))
	->hint(Yii::t('app', 'If set to "yes", users can block other users from sending them private messages, requesting their friendship, and viewing their profile. This helps fight spam and network abuse.')); ?>

<?php $assignments = Assignments::getRoles();
echo $form->field($model, 'assignment_roles')
	->checkboxList($assignments)
	->label($model->getAttributeLabel('assignment_roles'))
	->hint(Yii::t('app', '')); ?>

<?php $profileSearch = UserLevel::getProfileSearch();
echo $form->field($model, 'profile_search', ['template' => '{label}{beginWrapper}<div class="h5">'.$model->getAttributeLabel('profile_search').'</div>{hint}{input}{error}{endWrapper}'])
	->radioList($profileSearch)
	->label(Yii::t('app', 'Privacy Options'))
	->hint(Yii::t('app', 'If you enable this feature, users will be able to exclude themselves from search results and the lists of users on the homepage (such as Recent Signups). Otherwise, all users will be included in search results.')); ?>

<?php $profilePrivacy = UserLevel::getProfilePrivacy();
echo $form->field($model, 'profile_privacy', ['template' => '{beginWrapper}<div class="h5">'.$model->getAttributeLabel('profile_privacy').'</div>{hint}{input}{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->checkboxList($profilePrivacy)
	->label($model->getAttributeLabel('profile_privacy'))
	->hint(Yii::t('app', 'Your users can choose from any of the options checked below when they decide who can see their profile. If you do not check any options, everyone will be allowed to view profiles.')); ?>

<?php $profileComments = UserLevel::getProfilePrivacy();
echo $form->field($model, 'profile_comments', ['template' => '{beginWrapper}<div class="h5">'.$model->getAttributeLabel('profile_comments').'</div>{hint}{input}{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->checkboxList($profileComments)
	->label($model->getAttributeLabel('profile_comments'))
	->hint(Yii::t('app', 'Your users can choose from any of the options checked below when they decide who can post comments on their profile. If you do not check any options, everyone will be allowed to post comments on profiles.')); ?>

<?php $photoAllow = UserLevel::getPhotoAllow();
echo $form->field($model, 'photo_allow', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($photoAllow)
	->label($model->getAttributeLabel('photo_allow'))
	->hint(Yii::t('app', 'If you enable this feature, users can upload a small photo icon of themselves. This will be shown next to their name/username on their profiles, in search/browse results, next to their private messages, etc.')); ?>

<?php $photo_size_width = $form->field($model, 'photo_size[width]', ['template' => '<div class="col-sm-3">'.Yii::t('app', 'Maximum Width:').'</div><div class="col-sm-3">{input}</div><div class="col-sm-6">in pixels, between 1 and 999</div><div class="clearfix"></div>', 'options' => ['class' => 'row']])
	->textInput(['type' => 'number', 'min'=>0, 'maxlength' => true])
	->label($model->getAttributeLabel('photo_size')); ?>

<?php $photo_size_height = $form->field($model, 'photo_size[height]', ['template' => '<div class="col-sm-3">'.Yii::t('app', 'Maximum Height:').'</div><div class="col-sm-3">{input}</div><div class="col-sm-6">in pixels, between 1 and 999</div><div class="clearfix"></div>', 'options' => ['class' => 'row']])
	->textInput(['type'=>'number', 'min'=>0,'maxlength' => true])
	->label($model->getAttributeLabel('photo_size')); ?>

<?php $model->photo_size = serialize($model->photo_size);
echo $form->field($model, 'photo_size', ['template' => '{beginWrapper}{hint}'.$photo_size_width.$photo_size_height.'{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->label($model->getAttributeLabel('photo_size'))
	->hint(Yii::t('app', 'If you have selected "Yes" above, please input the maximum dimensions for the user photos. If your users upload a photo that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.')); ?>

<?php echo $form->field($model, 'photo_exts')
	->textInput()
	->label($model->getAttributeLabel('photo_exts'))
	->hint(Yii::t('app', 'What file types do you want to allow for user photos (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png')); ?>

<?php $profileStyle = UserLevel::getProfileStyle();
echo $form->field($model, 'profile_style', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileStyle)
	->label($model->getAttributeLabel('profile_style'))
	->hint(Yii::t('app', 'Enable this feature if you want to allow users to customize the colors and fonts of their profiles with their own CSS styles.')); ?>

<?php $profileStyleSample = UserLevel::getProfileStyleSample();
echo $form->field($model, 'profile_style_sample', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileStyleSample)
	->label($model->getAttributeLabel('profile_style_sample'))
	->hint(Yii::t('app', 'Enable this feature if you want your users to choose from existing CSS samples. To add additional samples, simply insert a row into the se_stylesamples database table containing the exact CSS code that should be entered into the Profile Style textarea and, optionally, the path to a thumbnail for the template.')); ?>

<?php $profileStatus = UserLevel::getProfileStatus();
echo $form->field($model, 'profile_status', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileStatus)
	->label($model->getAttributeLabel('profile_status'))
	->hint(Yii::t('app', 'Enable this feature if you want to allow users to show their "status" on their profile. By updating their status, users can tell others what they are up to, what\'s on their minds, etc.')); ?>

<?php $profileInvisible = UserLevel::getProfileInvisible();
echo $form->field($model, 'profile_invisible', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileInvisible)
	->label($model->getAttributeLabel('profile_invisible'))
	->hint(Yii::t('app', 'Enable this feature if you want to allow users to go "invisible" (not be displayed in the online users list even if they are online).')); ?>

<?php $profileViews = UserLevel::getProfileViews();
echo $form->field($model, 'profile_views', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileViews)
	->label($model->getAttributeLabel('profile_views'))
	->hint(Yii::t('app', 'If you enable this feature, users will be given the option of seeing which users have visited their profile.')); ?>

<?php $profileChange = UserLevel::getProfileChangeUsername();
echo $form->field($model, 'profile_change', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileChange)
	->label($model->getAttributeLabel('profile_change'))
	->hint(Yii::t('app', 'Enable this feature if you want to allow your users to be able to change their username. Note that if you have usernames disabled on the General Settings page, this feature is irrelevant.')); ?>

<?php $profileDelete = UserLevel::getProfileDeleted();
echo $form->field($model, 'profile_delete', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($profileDelete)
	->label($model->getAttributeLabel('profile_delete'))
	->hint(Yii::t('app', 'Enable this feature if you would like to allow your users to delete their account manually.')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>