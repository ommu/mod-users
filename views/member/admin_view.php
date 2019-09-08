<?php
/**
 * Users (users)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\MemberController
 * @var $model ommu\users\models\Users
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\users\models\Users;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->user_id]), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->user_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="users-view">

<?php
$attributes = [
	[
		'attribute' => 'user_id',
		'value' => $model->user_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'enabled',
		'value' => Users::getEnabled($model->enabled),
		'visible' => !$small,
	],
	[
		'attribute' => 'verified',
		'value' => $this->quickAction(Url::to(['verified', 'id'=>$model->primaryKey]), $model->verified, 'Verified,Unverified'),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'level_id',
		'value' => isset($model->level) ? $model->level->title->message : '-',
	],
	[
		'attribute' => 'language_id',
		'value' => isset($model->languageRltn) ? $model->languageRltn->name : '-',
		'visible' => !$small,
	],
	'email:email',
	[
		'attribute' => 'displayname',
		'value' => $model->displayname ? $model->displayname : '-',
	],
	[
		'attribute' => 'username',
		'value' => $model->username ? $model->username : '-',
	],
	[
		'attribute' => 'password',
		'value' => $model->password,
		'visible' => !$small,
	],
	[
		'attribute' => 'salt',
		'value' => $model->salt,
		'visible' => !$small,
	],
	[
		'attribute' => 'deactivate',
		'value' => $this->filterYesNo($model->deactivate),
		'visible' => !$small,
	],
	[
		'attribute' => 'search',
		'value' => $this->filterYesNo($model->search),
		'visible' => !$small,
	],
	[
		'attribute' => 'invisible',
		'value' => $this->filterYesNo($model->invisible),
		'visible' => !$small,
	],
	[
		'attribute' => 'privacy',
		'value' => $this->filterYesNo($model->privacy),
		'visible' => !$small,
	],
	[
		'attribute' => 'comments',
		'value' => $this->filterYesNo($model->comments),
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_ip',
		'value' => $model->creation_ip,
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'lastlogin_date',
		'value' => Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'lastlogin_ip',
		'value' => $model->lastlogin_ip,
		'visible' => !$small,
	],
	[
		'attribute' => 'lastlogin_from',
		'value' => $model->lastlogin_from,
		'visible' => !$small,
	],
	[
		'attribute' => 'update_date',
		'value' => Yii::$app->formatter->asDatetime($model->update_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'update_ip',
		'value' => $model->update_ip,
		'visible' => !$small,
	],
	[
		'attribute' => 'auth_key',
		'value' => $model->auth_key ? $model->auth_key : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'jwt_claims',
		'value' => $model->jwt_claims ? $model->jwt_claims : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'assignmentRoles',
		'value' => is_array($model->assignmentRoles) && !empty($model->assignmentRoles) ? implode(', ', $model->assignmentRoles) : '-',
		'visible' => !$small,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>