<?php
/**
 * NewsletterController
 * @var $this yii\web\View
 * @var $model ommu\users\models\UserNewsletter
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
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 14 November 2018, 01:24 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
 
namespace ommu\users\controllers\manage;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\users\models\UserNewsletter;
use ommu\users\models\search\UserNewsletter as UserNewsletterSearch;

class NewsletterController extends Controller
{
	use \ommu\traits\FileTrait;

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

		$this->view->title = Yii::t('app', 'Newsletters');
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

			$email_i = $this->formatFileType($model->email_i);
			if(count($email_i) == 1)
				$model->scenario = UserNewsletter::SCENARIO_SINGLE_EMAIL;

			$result = [];
			if($model->validate()) {
				if(count($email_i) > 1) {
					foreach ($email_i as $email) {
						$condition = UserNewsletter::insertNewsletter($email);
						if($condition == 0)
							$result[] = Yii::t('app', '{email} (skip)', array('email'=>$email));
						else if($condition == 1)
							$result[] = Yii::t('app', '{email} (success)', array('email'=>$email));
						else if($condition == 2)
							$result[] = Yii::t('app', '{email} (error)', array('email'=>$email));
					}
					Yii::$app->session->setFlash('success', Yii::t('app', 'Newsletter success created.<br/>{result}', ['result'=>$this->formatFileType($result, false, '<br/>')]));
					return $this->redirect(['index']);

				} else {
					if($model->save()) {
						Yii::$app->session->setFlash('success', Yii::t('app', 'User newsletter {email} success created.', ['email'=>$model->email]));
						return $this->redirect(['index']);
					}
				}

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\yii\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Newsletter');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
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

		$this->view->title = Yii::t('app', 'Detail {model-class}: {user-id}', ['model-class' => 'Newsletter', 'user-id' => $model->email]);
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
		
		if($model->save(false, ['status','modified_id'])) {
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

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
