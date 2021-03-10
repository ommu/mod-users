<?php
/**
 * User Verifies (user-verify)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\VerifyController
 * @var $model ommu\users\models\UserVerify
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 17 October 2017, 15:00 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verifies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->user->displayname;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->verify_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="user-verify-view">

<?php
$attributes = [
    'verify_id',
    [
        'attribute' => 'publish',
        'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
        'format' => 'raw',
    ],
    [
        'attribute' => 'expired',
        'value' => $this->filterYesNo($model->expired),
    ],
    [
        'attribute' => 'userDisplayname',
        'value' => isset($model->user) ? $model->user->displayname : '-',
    ],
    [
        'attribute' => 'email_i',
        'value' => isset($model->user) ? Yii::$app->formatter->asEmail($model->user->email) : '-',
        'format' => 'html',
    ],
    [
        'attribute' => 'userLevel',
        'value' => isset($model->user->level) ? $model->user->level->title->message : '-',
    ],
    'code',
    [
        'attribute' => 'verify_date',
        'value' => Yii::$app->formatter->asDatetime($model->verify_date, 'medium'),
    ],
    'verify_ip',
    [
        'attribute' => 'expired_date',
        'value' => Yii::$app->formatter->asDatetime($model->expired_date, 'medium'),
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
    [
        'attribute' => 'deleted_date',
        'value' => Yii::$app->formatter->asDatetime($model->deleted_date, 'medium'),
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