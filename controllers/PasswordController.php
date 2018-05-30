<?php
/**
 * PasswordController
 * @var $this yii\web\View
 * @var $model ommu\users\models\UserForgot
 *
 * PasswordController implements the CRUD actions for UserForgot model.
 * Reference start
 * TOC :
 *	Forgot
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 30 May 2018, 11:27 WIB
 * @modified date 30 May 2018, 11:54 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */
 
namespace ommu\users\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use ommu\users\models\UserForgot;
use yii\data\ActiveDataProvider;

class PasswordController extends Controller
{
	/**
	 * Creates a new UserForgot model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionForgot()
	{
		$model = new UserForgot();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User forgot success created.'));
				return $this->redirect(['forgot']);
				//return $this->redirect(['view', 'id' => $model->forgot_id]);
			} 
		}

		static::$backoffice = false;
		$this->view->title = Yii::t('app', 'Create User Forgot');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('front_forgot', [
			'model' => $model,
		]);
	}






	/**
	 * Lists all UserForgot models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => UserForgot::find(),
		]);

		$this->view->title = Yii::t('app', 'User Forgots');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'dataProvider' => $dataProvider,
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
				//return $this->redirect(['view', 'id' => $model->forgot_id]);
			} 
		}

		$this->view->title = Yii::t('app', 'Create User Forgot');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing UserForgot model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User forgot success updated.'));
				return $this->redirect(['index']);
				//return $this->redirect(['view', 'id' => $model->forgot_id]);
			}
		}

		$this->view->title = Yii::t('app', 'Update {model-class}: {user-id}', ['model-class' => 'User Forgot', 'user-id' => $model->user->username]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
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

		$this->view->title = Yii::t('app', 'Detail {model-class}: {user-id}', ['model-class' => 'User Forgot', 'user-id' => $model->user->username]);
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
			//return $this->redirect(['view', 'id' => $model->forgot_id]);
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
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
