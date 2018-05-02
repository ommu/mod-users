<?php
namespace app\coremodules\user\controllers;

use Yii;
use app\coremodules\user\models\search\User as UserSearch;

class UserController extends \app\components\Controller
{
    public $layout = 'backoffice';

    public function actionIndex() {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
