<?php
/**
 * PersonalController
 * @var $this yii\web\View
 * @var $model ommu\users\models\Users
 *
 * PersonalController implements the CRUD actions for Users model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	Enabled
 *	Verified
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @modified date 15 November 2018, 07:04 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-users
 *
 */
 
namespace ommu\users\controllers\manage;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
// use ommu\users\models\Users;
use ommu\users\models\search\Users as UsersSearch;
use app\modules\user\models\Users;

class PersonalController extends Controller
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

		$this->view->title = Yii::t('app', 'Personals');
		$this->view->description = Yii::t('app', 'This page lists all of the users that exist on your social network. For more information about a specific user, click on the "edit" link in its row. Click the "login" link to login as a specific user. Use the filter fields to find specific users based on your criteria. To view all users on your system, leave all the filter fields blank.');
		$this->view->keywords = '';
		return $this->render('admin_index', [
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
		return $this->oRender('admin_create', [
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
		$this->view->description = Yii::t('app', 'To edit this users\'s account, make changes to the form below. If you want to temporarily prevent this user from logging in, you can set the user account to "disabled" below.');
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
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
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Users model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'User success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * actionVerified an existing Users model.
	 * If verified is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionVerified($id)
	{
		$model = $this->findModel($id);
		$replace = $model->verified == 1 ? 0 : 1;
		$model->verified = $replace;
		
		if($model->save(false, ['verified','modified_date','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * actionEnabled an existing Users model.
	 * If enabled is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionEnabled($id)
	{
		$model = $this->findModel($id);
		$replace = $model->enabled == 1 ? 0 : 1;
		$model->enabled = $replace;
		
		if($model->save(false, ['enabled','modified_date','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User success updated.'));
			return $this->redirect(['index']);
		}
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
		if(($model = Users::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
