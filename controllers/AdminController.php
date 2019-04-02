<?php
/**
 * AdminController
 * @var $this app\components\View
 * @var $model ommu\users\models\Users
 *
 * AdminController implements the CRUD actions for Users model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
 
namespace ommu\users\controllers;

use Yii;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;
use ommu\users\models\Users;
use ommu\users\models\search\Users as UsersSearch;
use ommu\users\controllers\MemberController;
use ommu\users\models\UserLevel;
// use app\modules\user\models\Users;

class AdminController extends MemberController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
					'enabled' => ['POST'],
					'verified' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Users models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UsersSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		$this->view->title = Yii::t('app', 'Administrators');
		$this->view->description = Yii::t('app', 'Your social network can have more than one administrator. This is useful if you want to have a staff of admins who maintain your social network. However, the first admin to be created (upon installation) is the "superadmin" and cannot be deleted. The superadmin can create and delete other admin accounts. All admin accounts on your system are listed below.');
		$this->view->keywords = '';
		return $this->render('/member/admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new Users model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Users();
		$model->scenario = Users::SCENARIO_ADMIN_CREATE;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User success created.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id'=>$model->user_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create User');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('/member/admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Users model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$postData = Yii::$app->request->post();
			$model->load($postData);
			if($postData['password'])
				$model->scenario = Users::SCENARIO_ADMIN_UPDATE_WITH_PASSWORD;
			$model->isForm = true;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id'=>$model->user_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}: {displayname}', ['model-class' => 'User', 'displayname' => $model->displayname]);
		$this->view->description = Yii::t('app', 'Complete the form below to add/edit this admin account. Note that normal admins will not be able to delete or modify the superadmin account. If you want to change this admin\'s password, enter both the old and new passwords below - otherwise, leave them both blank.');
		$this->view->keywords = '';
		return $this->oRender('/member/admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single Users model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {displayname}', ['model-class' => 'User', 'displayname' => $model->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('/member/admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the Users model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Users the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$level = UserLevel::getLevel('admin');
		$model = Users::find()
			->where(['user_id' => $id])
			->andWhere(['in', 'level_id', array_flip($level)])
			->one();
		
		if($model !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
