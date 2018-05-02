<?php
/**
 * VerifyController
 * @var $this yii\web\View
 * @var $model app\coremodules\user\models\UserVerify
 * version: 0.0.1
 *
 * VerifyController implements the CRUD actions for UserVerify model.
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
 * @created date 17 October 2017, 15:00 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\controllers;

use Yii;
use app\coremodules\user\models\UserVerify;
use app\coremodules\user\models\search\UserVerify as UserVerifySearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class VerifyController extends Controller
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
	 * Lists all UserVerify models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserVerifySearch();
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

		$this->view->title = Yii::t('app', 'User Verifies');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new UserVerify model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserVerify();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->verify_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Verify success created.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Create User Verify');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single UserVerify model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {verify_id}', ['modelClass' => 'User Verify', 'verify_id' => $model->verify_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserVerify model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->verify_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Verify success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the UserVerify model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserVerify the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = UserVerify::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
