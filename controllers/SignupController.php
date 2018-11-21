<?php
/**
 * SignupController
 * @var $this yii\web\View
 * @var $model app\modules\user\models\Users
 *
 * SignupController implements the CRUD actions for Users model.
 * Reference start
 * TOC :
 *	Index
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 19 November 2018, 06:26 WIB
 * @link https://github.com/ommu/mod-users
 *
 */
 
namespace ommu\users\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
// use ommu\users\models\Users;
use app\models\CoreSettings;
use app\modules\user\models\Users;

class SignupController extends Controller
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
		];
	}

	/**
	 * Lists all Users models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$setting = CoreSettings::find()
			->select(['site_type', 'signup_random','signup_inviteonly','signup_checkemail'])
			->where(['id' => 1])
			->one();

		$model = new Users();
		$model->scenario = Users::SCENARIO_REGISTER;
		if($setting->site_type == 1 && $setting->signup_inviteonly != 0 && $setting->signup_checkemail == 1)
			$model->scenario = Users::SCENARIO_REGISTER_WITH_INVITE_CODE;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'User success created.'));
				return $this->redirect(['index']);
			} 
		}

		$this->view->title = Yii::t('app', 'Signup');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_signup', [
			'model' => $model,
			'setting' => $setting,
		]);
	}

	public function allowAction(): array {
		return ['index'];
	}
}
