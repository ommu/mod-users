<?php
/**
 * InviteController
 * @var $this yii\web\View
 * @var $model ommu\users\models\UserInvites
 *
 * InviteController implements the CRUD actions for UserInvites model.
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
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 23 October 2017, 08:27 WIB
 * @modified date 8 May 2018, 00:41 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */
 
namespace ommu\users\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\users\models\UserInvites;
use ommu\users\models\search\UserInvites as UserInvitesSearch;

class InviteController extends Controller
{
	use \ommu\traits\FileTrait;

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
					'publish' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all UserInvites models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserInvitesSearch();
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

		$this->view->title = Yii::t('app', 'User Invites');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new UserInvites model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserInvites();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$result = [];
			if($model->validate()) {
				$email_i = $this->formatFileType($model->email_i);
				$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
				if(count($email_i) > 1) {
					foreach ($email_i as $email) {
						$condition = UserInvites::insertInvite($email, $user_id);
						if($condition == 0)
							$result[] = Yii::t('app', '{email} (skip)', array('email'=>$email));
						else if($condition == 1)
							$result[] = Yii::t('app', '{email} (success)', array('email'=>$email));
						else if($condition == 2)
							$result[] = Yii::t('app', '{email} (error)', array('email'=>$email));
					}
					Yii::$app->session->setFlash('success', Yii::t('app', 'User invite success created.<br/>{result}', ['result'=>$this->formatFileType($result, false, '<br/>')]));
					return $this->redirect(['index']);
					
				} else {
					if($model->save()) {
						Yii::$app->session->setFlash('success', Yii::t('app', 'User {email} invite success created.', ['email'=>$model->newsletter->email]));
						return $this->redirect(['index']);
						//return $this->redirect(['view', 'id' => $model->invite_id]);
					}
				}
			}
		}

		$this->view->title = Yii::t('app', 'Create User Invite');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single UserInvites model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {newsletter-id}', ['model-class' => 'User Invite', 'newsletter-id' => $model->newsletter->email]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing UserInvites model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'User invite success deleted.'));
			return $this->redirect(['index']);
			//return $this->redirect(['view', 'id' => $model->invite_id]);
		}
	}

	/**
	 * actionPublish an existing UserInvites model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'User invite success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the UserInvites model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return UserInvites the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = UserInvites::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
