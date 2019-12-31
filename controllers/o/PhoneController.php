<?php
/**
 * PhoneController
 * @var $this ommu\users\controllers\o\PhoneController
 * @var $model ommu\users\models\UserPhones
 *
 * PhoneController implements the CRUD actions for UserPhones model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *	Verified
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 14 November 2018, 15:16 WIB
 * @modified date 15 November 2018, 09:50 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\controllers\o;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\users\models\UserPhones;
use ommu\users\models\search\UserPhones as UserPhonesSearch;

class PhoneController extends Controller
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
					'publish' => ['POST'],
					'verified' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all UserPhones models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserPhonesSearch();
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

		$this->view->title = Yii::t('app', 'Phones');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new UserPhones model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$user = Yii::$app->request->get('user');
		if(!$user)
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new UserPhones();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->user_id = $user;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User phone success created.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id'=>$model->phone_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Phone');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing UserPhones model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User phone success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id'=>$model->phone_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Phone: {user-id}', ['user-id' => $model->user->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single UserPhones model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Phone: {user-id}', ['user-id' => $model->user->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserPhones model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User phone success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * actionPublish an existing UserPhones model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User phone success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * actionVerified an existing UserPhones model.
	 * If verified is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionVerified($id)
	{
		$model = $this->findModel($id);
		$replace = $model->verified == 1 ? 0 : 1;
		$model->verified = $replace;
		
		if($model->save(false, ['verified','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User phone success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the UserPhones model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserPhones the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = UserPhones::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
