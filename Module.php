<?php

namespace app\coremodules\user;

/**
 * user module definition class
 */
class Module extends \app\components\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\coremodules\user\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
