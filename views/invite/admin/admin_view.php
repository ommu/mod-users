<?php
/**
 * User Invites (user-invites)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\invite\AdminController
 * @var $model ommu\users\models\UserInvites
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:27 WIB
 * @modified date 13 November 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="user-invites-view">

<?php
$attributes = [
    'id',
    [
        'attribute' => 'publish',
        'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
        'format' => 'raw',
    ],
    'displayname',
    [
        'attribute' => 'email_search',
        'value' => isset($model->newsletter) ? Yii::$app->formatter->asEmail($model->newsletter->email) : '-',
        'format' => 'html',
    ],
    'code',
    'invites',
    [
        'attribute' => 'inviter_search',
        'value' => isset($model->inviter) ? $model->inviter->displayname : '-',
    ],
    [
        'attribute' => 'userLevel',
        'value' => isset($model->inviter->level) ? $model->inviter->level->title->message : '-',
    ],
    [
        'attribute' => 'invite_date',
        'value' => Yii::$app->formatter->asDatetime($model->invite_date, 'medium'),
    ],
    'invite_ip',
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
        'attribute' => 'updated_date',
        'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
        'visible' => !$small,
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