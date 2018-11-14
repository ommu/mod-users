<?php
/**
 * ForgotController
 * @var $this yii\web\View
 * @var $model ommu\users\models\UserForgot
 *
 * ForgotController implements the CRUD actions for UserForgot model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 17 October 2017, 15:01 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
 
namespace ommu\users\controllers\manage;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\users\models\UserForgot;
use ommu\users\models\search\UserForgot as UserForgotSearch;

class ForgotController extends Controller
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

		$this->view->title = Yii::t('app', 'Forgots');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
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

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User forgot success created.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id'=>$model->forgot_id]);
			} 
		}

		$this->view->title = Yii::t('app', 'Create Forgot');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single UserForgot model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {user-id}', ['model-class' => 'Forgot', 'user-id' => $model->user->displayname]);
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

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User forgot success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * actionPublish an existing UserForgot model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		if($model->publish == 0)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User forgot success updated.'));
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
		if(($model = UserForgot::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
