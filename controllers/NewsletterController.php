<?php
/**
 * NewsletterController
 * @var $this yii\web\View
 * @var $model app\modules\user\models\UserNewsletter
 *
 * NewsletterController implements the CRUD actions for UserNewsletter model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	View
 *	Delete
 *	Status
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 15:59 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */
 
namespace app\modules\user\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\modules\user\models\UserNewsletter;
use app\modules\user\models\search\UserNewsletter as UserNewsletterSearch;

class NewsletterController extends Controller
{
	/**
	 * @inheritdoc
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
					'status' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all UserNewsletter models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserNewsletterSearch();
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

		$this->view->title = Yii::t('app', 'User Newsletters');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new UserNewsletter model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserNewsletter();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User newsletter success created.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->newsletter_id]);
			} 
		}

		$this->view->title = Yii::t('app', 'Create User Newsletter');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single UserNewsletter model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {email}', ['model-class' => 'User Newsletter', 'email' => $model->email]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserNewsletter model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'User newsletter success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * actionStatus an existing UserNewsletter model.
	 * If status is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionStatus($id)
	{
		$model = $this->findModel($id);
		$replace = $model->status == 1 ? 0 : 1;
		$model->status = $replace;
		
		if($model->save(false, ['status'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User newsletter success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the UserNewsletter model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserNewsletter the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = UserNewsletter::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
