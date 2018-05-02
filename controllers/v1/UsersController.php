<?php

namespace app\modules\user\controllers\v1;

use Yii;
use app\components\api\ActiveController;
use app\modules\user\models\search\Users as UsersSearch;

class UsersController extends ActiveController
{
	public $modelClass = 'app\modules\user\models\Users';
	public $searchModelClass = 'app\modules\user\models\search\Users';
	public static $authType = 2;

	// https://stackoverflow.com/questions/25522462/yii2-rest-query#answer-25618361
	// public function actions() {
	//	 $actions = parent::actions();
	//	 $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
	//	 return $actions;
	// }

	// public function prepareDataProvider() {
	//	 $searchModel = new UsersSearch();
	//	 $limit = Yii::$app->request->get('limit', 20);
	//	 $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	//	 $dataProvider->pagination->setPageSize($limit);
	//	 return $dataProvider;
	// }

	// TODO: hapus jika sudah di server live!
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['bearerAuth']['except'] = ['index', 'create'];
		return $behaviors;
	}

	public function afterAction($action, $result)
	{
		$result = parent::afterAction($action, $result);
		return $result;
	}
}
