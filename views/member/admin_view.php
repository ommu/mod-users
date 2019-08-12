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

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'user_id',
		[
			'attribute' => 'enabled',
			'value' => Users::getEnabled($model->enabled),
		],
		[
			'attribute' => 'verified',
			'value' => $this->quickAction(Url::to(['verified', 'id'=>$model->primaryKey]), $model->verified, 'Verified,Unverified'),
			'format' => 'raw',
		],
		[
			'attribute' => 'level_id',
			'value' => isset($model->level) ? $model->level->title->message : '-',
		],
		[
			'attribute' => 'language_id',
			'value' => isset($model->languageRltn) ? $model->languageRltn->name : '-',
		],
		'email:email',
		'displayname',
		'password',
		'salt',
		[
			'attribute' => 'deactivate',
			'value' => $this->filterYesNo($model->deactivate),
		],
		[
			'attribute' => 'search',
			'value' => $this->filterYesNo($model->search),
		],
		[
			'attribute' => 'invisible',
			'value' => $this->filterYesNo($model->invisible),
		],
		[
			'attribute' => 'privacy',
			'value' => $this->filterYesNo($model->privacy),
		],
		[
			'attribute' => 'comments',
			'value' => $this->filterYesNo($model->comments),
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		'creation_ip',
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'lastlogin_date',
			'value' => Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium'),
		],
		'lastlogin_ip',
		'lastlogin_from',
		[
			'attribute' => 'update_date',
			'value' => Yii::$app->formatter->asDatetime($model->update_date, 'medium'),
		],
		'update_ip',
		[
			'attribute' => 'auth_key',
			'value' => $model->auth_key ? $model->auth_key : '-',
		],
		[
			'attribute' => 'jwt_claims',
			'value' => $model->jwt_claims ? $model->jwt_claims : '-',
		],
		[
			'attribute' => 'assignmentRoles',
			'value' => is_array($model->assignmentRoles) && !empty($model->assignmentRoles) ? implode(', ', $model->assignmentRoles) : '-',
		],
	],
]) ?>

</div>