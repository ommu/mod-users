<?php
/**
 * SubscribeController
 * @var $this yii\web\View
 * @var $model app\coremodules\user\models\UserNewsletterHistory
 * version: 0.0.1
 *
 * SubscribeController implements the CRUD actions for UserNewsletterHistory model.
 * Reference start
 * TOC :
 *	Index
 *	Delete
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 23 October 2017, 08:29 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace app\coremodules\user\controllers\history;

use Yii;
use app\coremodules\user\models\UserNewsletterHistory;
use app\coremodules\user\models\search\UserNewsletterHistory as UserNewsletterHistorySearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SubscribeController extends Controller
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
	 * Lists all UserNewsletterHistory models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserNewsletterHistorySearch();
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

		$this->view->title = Yii::t('app', 'User Newsletter Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Deletes an existing UserNewsletterHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'User Newsletter History success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the UserNewsletterHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserNewsletterHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = UserNewsletterHistory::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
