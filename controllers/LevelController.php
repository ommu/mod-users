<?php
/**
 * LevelController
 * @var $this yii\web\View
 * @var $model app\modules\user\models\UserLevel
 * version: 0.0.1
 *
 * LevelController implements the CRUD actions for UserLevel model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	User
 *	Message
 *	Default
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 07:46 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\UserLevel;
use app\modules\user\models\search\UserLevel as UserLevelSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class LevelController extends Controller
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
					'default' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all UserLevel models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserLevelSearch();
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

		$this->view->title = Yii::t('app', 'User Levels');
		$this->view->description = Yii::t('app', 'If you want to put users into different groups with varying access to features (e.g. Bronze, Silver, and Gold membership plans), you can create multiple user groups. You must always have at least one group - your default group (which cannot be deleted). When users signup, they will be placed into the group you have designated as the default group on this page. You can change a user\'s group by editing their account from the View Users page. If you want to give all users on your social network the same features and limits, you will only need one user level.');
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new UserLevel model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserLevel();
		$model->scenario = 'info';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->level_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Level success created.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Create User Level');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing UserLevel model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'info';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Level success updated.'));
			return $this->redirect(['update', 'id' => $model->level_id]);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {name}', ['modelClass' => 'User Level', 'name' => $model->title->message]);
			$this->view->description = Yii::t('app', 'You are currently editing this user level\'s settings. Remember, these settings only apply to the users that belong to this user level. When you\'re finished, you can edit the other levels here.');
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single UserLevel model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {name}', ['modelClass' => 'User Level', 'name' => $model->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserLevel model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'User Level success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Updates an existing UserLevel model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUser($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'user';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Level success updated.'));
			return $this->redirect(['user', 'id' => $model->level_id]);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {name}', ['modelClass' => 'User Level', 'name' => $model->title->message]);
			$this->view->description = Yii::t('app', 'This page contains various settings that affect your users\' accounts.');
			$this->view->keywords = '';
			return $this->render('admin_user', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing UserLevel model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionMessage($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'message';

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Level success updated.'));
			return $this->redirect(['message', 'id' => $model->level_id]);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {name}', ['modelClass' => 'User Level', 'name' => $model->title->message]);
			$this->view->description = Yii::t('app', 'Facilitating user interactivity is the key to developing a successful social network. Allowing private messages between users is an excellent way to increase interactivity. From this page, you can enable the private messaging feature and configure its settings.');
			$this->view->keywords = '';
			return $this->render('admin_messsge', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Publish/Unpublish an existing CoreTags model.
	 * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDefault($id)
	{
		$model = $this->findModel($id);
		$model->default = 1;

		if ($model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User Level success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the UserLevel model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserLevel the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = UserLevel::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
