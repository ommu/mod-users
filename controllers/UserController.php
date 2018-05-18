<?php
namespace ommu\users\controllers;

use Yii;
use ommu\users\models\search\User as UserSearch;

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
