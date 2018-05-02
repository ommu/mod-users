<?php
use app\coremodules\user\Events;
use app\coremodules\user\components\User;

return [
    'id'           => 'user',
    'class'        => \app\coremodules\user\Module::className(),
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
