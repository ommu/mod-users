<?php
/**
 * LoginController
 * @var $this yii\web\View
 *
 * Reference start
 * TOC :
 *  Index
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 25 November 2018, 13:58 WIB
 * @link https://github.com/ommu/ommu
 *
 */
 
namespace ommu\users\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\modules\user\models\LoginForm;
use yii\validators\EmailValidator;

class LoginController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init() 
	{
		parent::init();

		$this->layout ='login';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function allowAction(): array {
		return ['index'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		$actions = parent::actions();
		if(Yii::$app->request->getContentType() == 'application/json' || Yii::$app->request->isAjax) {

			$this->enableCsrfValidation = false;
			$actions['index'] = [
				'class' => 'app\modules\user\actions\auth\ActionLogin',
			];
		}
		return $actions;
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		if(!Yii::$app->user->isGuest)
			return $this->goHome();

		$model = new LoginForm();
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->isAdmin = true;

			$validator = new EmailValidator();
			if($validator->validate($model->username) === true)
				$model->setByEmail(true);

			if($model->login())
				return $this->goBack();
		}

		$this->view->title = Yii::t('app', 'Login');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'model' => $model,
		]);
	}
}
