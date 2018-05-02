<?php
namespace app\modules\user\controllers;

class UserGroupController extends \app\components\Controller
{
    public function actionIndex() {
        return $this->render('index');
    }
}
