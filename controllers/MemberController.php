<?php
/**
 * MemberController
 * @var $this ommu\users\controllers\MemberController
 * @var $model ommu\users\models\Users
 *
 * MemberController implements the CRUD actions for Users model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	Enabled
 *	Verified
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\users\models\Users;
use ommu\users\models\search\Users as UsersSearch;
use yii\helpers\Inflector;

class MemberController extends Controller
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
					'enabled' => ['POST'],
					'verified' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return ['suggest'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'suggest' => 'ommu\users\actions\MemberSuggestAction',
		];
	}

	/**
	 * Lists all Users models.
	 * @return mixed
	 */
	public function actionIndex()
	{
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

		$this->view->title = Yii::t('app', Inflector::pluralize($this->title));
        $this->view->description = Yii::t('app', 'This page lists all of the users that exist on your social network. 
            For more information about a specific user, click on the "edit" link in its row. Click the "login" link to 
            login as a specific user. Use the filter fields to find specific users based on your criteria. To view all 
            users on your system, leave all the filter fields blank.');
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new Users model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new Users();
		$model->scenario = Users::SCENARIO_ADMIN_CREATE;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{title} success created.', [
                    'title' => $this->title,
                ]));
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->user_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create {title}', [
            'title' => $this->title,
        ]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Users model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            if ($postData['password']) {
                $model->scenario = Users::SCENARIO_ADMIN_UPDATE_WITH_PASSWORD;
            }
            $model->isForm = true;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{title} success updated.', [
                    'title' => $this->title,
                ]));
                return $this->redirect(['index']);
                //return $this->redirect(['view', 'id' => $model->user_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update {title}: {displayname}', [
            'title' => $this->title, 
            'displayname' => $model->displayname,
        ]);
        $this->view->description = Yii::t('app', 'To edit this users\'s account, make changes to the form below. If you 
            want to temporarily prevent this user from logging in, you can set the user account to "disabled" below.');
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single Users model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {title}: {displayname}', [
            'title' => $this->title, 
            'displayname' => $model->displayname,
        ]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Users model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', '{title} success deleted.', [
            'title' => $this->title,
        ]));
		return $this->redirect(['index']);
	}

	/**
	 * actionVerified an existing Users model.
	 * If verified is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionVerified($id)
	{
		$model = $this->findModel($id);
		$replace = $model->verified == 1 ? 0 : 1;
		$model->verified = $replace;
		
        if ($model->save(false, ['verified', 'modified_date', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', '{title} success updated.', [
                'title' => $this->title,
            ]));
			return $this->redirect(['index']);
		}
	}

	/**
	 * actionEnabled an existing Users model.
	 * If enabled is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionEnabled($id)
	{
		$model = $this->findModel($id);
		$replace = $model->enabled == 1 ? 0 : 1;
		$model->enabled = $replace;
		
        if ($model->save(false, ['enabled', 'modified_date', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', '{title} success updated.', [
                'title' => $this->title,
            ]));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the Users model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Users the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'member';
	}

	/**
	 * Title of Location.
	 * @return string
	 */
	public function getTitle()
	{
		return Yii::t('app', 'Member');
	}
}
