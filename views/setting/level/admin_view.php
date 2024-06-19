<?php
/**
 * User Levels (user-level)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\setting\LevelController
 * @var $model ommu\users\models\UserLevel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 9 November 2018, 10:32 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\users\models\UserLevel;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => Url::to(['setting/admin/index'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;
?>

<div class="user-level-view">

<?php
$attributes = [
    'level_id',
    [
        'attribute' => 'name_i',
        'value' => $model->name_i,
    ],
    [
        'attribute' => 'desc_i',
        'value' => $model->desc_i,
    ],
    [
        'attribute' => 'default',
        'value' => $model->quickAction(Url::to(['default', 'id' => $model->primaryKey]), $model->default, 'Default,No', true),
        'format' => 'raw',
    ],
    [
        'attribute' => 'signup',
        'value' => $model->quickAction(Url::to(['signup', 'id' => $model->primaryKey]), $model->default, 'Enable,Disable'),
        'format' => 'raw',
    ],
    [
        'attribute' => 'assignment_roles',
        'value' => $model::parseAssignment($model->assignment_roles),
        'format' => 'html',
    ],
    [
        'attribute' => 'message_allow',
        'value' => $this->filterYesNo($model->message_allow),
    ],
    [
        'attribute' => 'message_limit',
        'value' => serialize($model->message_limit),
    ],
    [
        'attribute' => 'profile_block',
        'value' => UserLevel::getProfileBlock($model->profile_block),
    ],
    [
        'attribute' => 'profile_search',
        'value' => UserLevel::getProfileSearch($model->profile_search),
    ],
    [
        'attribute' => 'profile_privacy',
        'value' => serialize($model->profile_privacy),
    ],
    [
        'attribute' => 'profile_comments',
        'value' => serialize($model->profile_comments),
    ],
    [
        'attribute' => 'profile_style',
        'value' =>  UserLevel::getProfileStyle($model->profile_style),
    ],
    [
        'attribute' => 'profile_style_sample',
        'value' => UserLevel::getProfileStyleSample($model->profile_style_sample),
    ],
    [
        'attribute' => 'profile_status',
        'value' => UserLevel::getProfileStatus($model->profile_status),
    ],
    [
        'attribute' => 'profile_invisible',
        'value' => UserLevel::getProfileInvisible($model->profile_invisible),
    ],
    [
        'attribute' => 'profile_views',
        'value' => UserLevel::getProfileViews($model->profile_views),
    ],
    [
        'attribute' => 'profile_change',
        'value' => UserLevel::getProfileChangeUsername($model->profile_change),
    ],
    [
        'attribute' => 'profile_delete',
        'value' => UserLevel::getProfileDeleted($model->profile_delete),
    ],
    [
        'attribute' => 'photo_allow',
        'value' => UserLevel::getPhotoAllow($model->photo_allow),
    ],
    [
        'attribute' => 'photo_size',
        'value' => UserLevel::getSize($model->photo_size),
    ],
    [
        'attribute' => 'photo_exts',
        'value' => $model->photo_exts,
    ],
    [
        'attribute' => 'creation_date',
        'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
        'visible' => !$small,
    ],
    [
        'attribute' => 'creationDisplayname',
        'value' => isset($model->creation) ? $model->creation->displayname : '-',
    ],
    [
        'attribute' => 'modified_date',
        'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
        'visible' => !$small,
    ],
    [
        'attribute' => 'modifiedDisplayname',
        'value' => isset($model->modified) ? $model->modified->displayname : '-',
    ],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>