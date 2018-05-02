<?php
/**
 * ForgotController
 * @var $this yii\web\View
 * @var $model app\coremodules\user\models\UserForgot
 * version: 0.0.1
 *
 * ForgotController implements the CRUD actions for UserForgot model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	View
 *	Delete
 *	RunAction
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 17 October 2017, 15:01 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace app\coremodules\user\controllers;

use Yii;
use app\coremodules\user\models\UserForgot;
use app\coremodules\user\models\search\UserForgot as UserForgotSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ForgotController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all UserForgot models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserForgotSearch();
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

		$this->view->title = Yii::t('app', 'User Forgots');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new UserForgot model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserForgot();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->forgot_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Forgot success created.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Create User Forgot');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single UserForgot model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {forgot_id}', ['modelClass' => 'User Forgot', 'forgot_id' => $model->forgot_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserForgot model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->forgot_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Forgot success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the UserForgot model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserForgot the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = UserForgot::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
