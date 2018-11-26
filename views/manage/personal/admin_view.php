<?php
/**
 * Users (users)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\manage\PersonalController
 * @var $model ommu\users\models\Users
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use ommu\users\models\Users;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->user_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->user_id]), 'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'method'=>'post', 'icon' => 'trash'],
];
?>

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
			'value' => isset($model->language) ? $model->language->name : '-',
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
			'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
		],
		'creation_ip',
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'lastlogin_date',
			'value' => !in_array($model->lastlogin_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->lastlogin_date, 'datetime') : '-',
		],
		'lastlogin_ip',
		'lastlogin_from',
		[
			'attribute' => 'update_date',
			'value' => !in_array($model->update_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->update_date, 'datetime') : '-',
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
	],
]) ?>

</div>