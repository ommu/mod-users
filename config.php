<?php
use app\modules\user\Events;
use app\modules\user\components\User;

return [
    'id'           => 'user',
    'class'        => \app\modules\user\Module::className(),
    'isCoreModule' => true,
    'events' => [
        ['class' => User::className(), 'event' => User::EVENT_INVALIDATE_CACHE,
            'callback' => [Events::className(), 'onBeforeLogout']],
    ],
    'urlManagerRules' => [
        'class'      => 'yii\rest\UrlRule',
        'controller' => [
            'user/v1/users',
        ],
        'pluralize'  => false,
    ],
];
