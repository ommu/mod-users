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
$this->params['breadcrumbs'][] = Yii::t('app', 'User');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/admin/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Info'), 'url' => Url::to(['update', 'id'=>$model->level_id]), 'icon' => 'info'],
	['label' => Yii::t('app', 'User'), 'url' => Url::to(['user', 'id'=>$model->level_id]), 'icon' => 'users'],
	['label' => Yii::t('app', 'Message'), 'url' => Url::to(['message', 'id'=>$model->level_id]), 'icon' => 'comment'],
	['label' => Yii::t('app', 'View'), 'url' => Url::to(['view', 'id'=>$model->level_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->level_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<div class="user-level-update-user">

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

<?php $profileBlock = UserLevel::getProfileBlock();
echo $form->field($model, 'profile_block', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'If set to "yes", users can block other users from sending them private messages, requesting their friendship, and viewing their profile. This helps fight spam and network abuse.').'</span>{input}{error}</div>'])
->radioList($profileBlock, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_block'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileSearch = UserLevel::getProfileSearch();
echo $form->field($model, 'profile_search', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><div class="h5">'.$model->getAttributeLabel('profile_search').'</div><span class="small-px">'.Yii::t('app', 'If you enable this feature, users will be able to exclude themselves from search results and the lists of users on the homepage (such as Recent Signups). Otherwise, all users will be included in search results.').'</span>{input}{error}</div>'])
	->radioList($profileSearch, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label(Yii::t('app', 'Privacy Options'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profilePrivacy = UserLevel::getProfilePrivacy();
echo $form->field($model, 'profile_privacy', ['template' => '<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3"><div class="h5">'.$model->getAttributeLabel('profile_privacy').'</div><span class="small-px">'.Yii::t('app', 'Your users can choose from any of the options checked below when they decide who can see their profile. If you do not check any options, everyone will be allowed to view profiles.').'</span>{input}{error}</div>'])
	->checkboxList($profilePrivacy, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_privacy'), ['class'=>'control-label col-md-6 col-sm-9 col-xs-12']); ?>

<?php $profileComments = UserLevel::getProfilePrivacy();
echo $form->field($model, 'profile_comments', ['template' => '<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3"><div class="h5">'.$model->getAttributeLabel('profile_comments').'</div><span class="small-px">'.Yii::t('app', 'Your users can choose from any of the options checked below when they decide who can post comments on their profile. If you do not check any options, everyone will be allowed to post comments on profiles.').'</span>{input}{error}</div>'])
	->checkboxList($profileComments, ['class'=>'desc pt-10', 'separator' => '<br />'])
	->label($model->getAttributeLabel('profile_comments'), ['class'=>'control-label col-md-6 col-sm-9 col-xs-12']); ?>

<?php $photoAllow = UserLevel::getPhotoAllow();
echo $form->field($model, 'photo_allow', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'If you enable this feature, users can upload a small photo icon of themselves. This will be shown next to their name/username on their profiles, in search/browse results, next to their private messages, etc.').'</span>{input}{error}</div>'])
	->radioList($photoAllow, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('photo_allow'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $photo_size_width = $form->field($model, 'photo_size[width]', ['template' => '<div class="col-md-3 col-sm-3 pt-5">'.Yii::t('app', 'Maximum Width:').'</div><div class="col-md-3 col-sm-3">{input}</div><div class="col-md-6 col-sm-6 pt-5">in pixels, between 1 and 999</div><div class="clearfix"></div>', 'options' => ['class' => 'row']])
	->textInput(['type' => 'number', 'min'=>0, 'maxlength' => true])
	->label($model->getAttributeLabel('photo_size'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $photo_size_height = $form->field($model, 'photo_size[height]', ['template' => '<div class="col-md-3 col-sm-3 pt-5">'.Yii::t('app', 'Maximum Height:').'</div><div class="col-md-3 col-sm-3">{input}</div><div class="col-md-6 col-sm-6 pt-5">in pixels, between 1 and 999</div><div class="clearfix"></div>', 'options' => ['class' => 'row']])
	->textInput(['type'=>'number', 'min'=>0,'maxlength' => true])
	->label($model->getAttributeLabel('photo_size'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $model->photo_size = serialize($model->photo_size);
echo $form->field($model, 'photo_size', ['template' => '<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3"><span class="small-px mb-10">'.Yii::t('app', 'If you have selected "Yes" above, please input the maximum dimensions for the user photos. If your users upload a photo that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.').'</span>'.$photo_size_width.$photo_size_height.'{error}</div>'])
	->label($model->getAttributeLabel('photo_size'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'photo_exts', ['template' => '<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3"><span class="small-px mb-10">'.Yii::t('app', 'What file types do you want to allow for user photos (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png').'</span>{input}{error}</div>'])
	->textInput()
	->label($model->getAttributeLabel('photo_exts'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileStyle = UserLevel::getProfileStyle();
echo $form->field($model, 'profile_style', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Enable this feature if you want to allow users to customize the colors and fonts of their profiles with their own CSS styles.').'</span>{input}{error}</div>'])
	->radioList($profileStyle, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_style'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileStyleSample = UserLevel::getProfileStyleSample();
echo $form->field($model, 'profile_style_sample', ['template' => '<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3"><span class="small-px">'.Yii::t('app', 'Enable this feature if you want your users to choose from existing CSS samples. To add additional samples, simply insert a row into the se_stylesamples database table containing the exact CSS code that should be entered into the Profile Style textarea and, optionally, the path to a thumbnail for the template.').'</span>{input}{error}</div>'])
	->radioList($profileStyleSample, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_style_sample'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileStatus = UserLevel::getProfileStatus();
echo $form->field($model, 'profile_status', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Enable this feature if you want to allow users to show their "status" on their profile. By updating their status, users can tell others what they are up to, what\'s on their minds, etc.').'</span>{input}{error}</div>'])
	->radioList($profileStatus, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_status'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileInvisible = UserLevel::getProfileInvisible();
echo $form->field($model, 'profile_invisible', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Enable this feature if you want to allow users to go "invisible" (not be displayed in the online users list even if they are online).').'</span>{input}{error}</div>'])
	->radioList($profileInvisible, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_invisible'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileViews = UserLevel::getProfileViews();
echo $form->field($model, 'profile_views', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'If you enable this feature, users will be given the option of seeing which users have visited their profile.').'</span>{input}{error}</div>'])
	->radioList($profileViews, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_views'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileChange = UserLevel::getProfileChangeUsername();
echo $form->field($model, 'profile_change', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Enable this feature if you want to allow your users to be able to change their username. Note that if you have usernames disabled on the General Settings page, this feature is irrelevant.').'</span>{input}{error}</div>'])
	->radioList($profileChange, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_change'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $profileDelete = UserLevel::getProfileDeleted();
echo $form->field($model, 'profile_delete', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12"><span class="small-px">'.Yii::t('app', 'Enable this feature if you would like to allow your users to delete their account manually.').'</span>{input}{error}</div>'])
	->radioList($profileDelete, ['class'=>'desc pt-10', 'separator'=>'<br />'])
	->label($model->getAttributeLabel('profile_delete'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>