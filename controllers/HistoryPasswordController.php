<?php
/**
 * HistoryPasswordController
 * @var $this yii\web\View
 * @var $model app\modules\user\models\UserHistoryPassword
 * version: 0.0.1
 *
 * HistoryPasswordController implements the CRUD actions for UserHistoryPassword model.
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
 * @created date 8 October 2017, 05:39 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace app\modules\user\controllers\history;

use Yii;
use app\modules\user\models\UserHistoryPassword;
use app\modules\user\models\search\UserHistoryPassword as UserHistoryPasswordSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class HistoryPasswordController extends Controller
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
	 * Lists all UserHistoryPassword models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserHistoryPasswordSearch();
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

		$this->view->title = Yii::t('app', 'User History Passwords');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Deletes an existing UserHistoryPassword model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'User History Password success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the UserHistoryPassword model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserHistoryPassword the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = UserHistoryPassword::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
