<?php
/**
 * ForgotController
 * @var $this ForgotController
 * @var $model UserForgot
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Code
 *	Post
 *	Get
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class ForgotController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
		$this->dialogFixed = true;
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','code','post','get'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$this->redirect(array('get'));
	}

	/**
	 * Lists all models.
	 */
	public function actionCode() 
	{
		/**
		 * example get link
		 * http://localhost/nirwasita_20130123/users/verify/code/key/hiHIDEdefgqrwxstwxBCfgnorsFGbckmbcmnnouvfgabzAEFfgophiqrFGqrpqab/secret/ijyzBCGHwxopcddeCDuvwxtufgzAuvst
		 * secret = salt[Users]
		 * key = code[UserVerify]
		 */

		Yii::app()->params['reset_user_id'] = 0;
		$render = 0;

		if(isset($_GET['key']) && isset($_GET['secret'])) {
			$secret = Users::model()->findByAttributes(array('salt' => $_GET['secret']), array(
				'select' => 'user_id, email',
			));
			$key = UserForgot::model()->findByAttributes(array('code' => $_GET['key']), array(
				'select' => 'forgot_id, user_id, forgot_date',
			));

			if($key != null && $secret != null) {
				if($secret->email == $key->user->email) {
					if(Utility::getDifferenceDay($key->forgot_date, date('Y-m-d H:i:s')) > 3) {
						$key->delete();
						$title = Yii::t('phrase', 'Forgot Password Expired Key');
						$desc = Yii::t('phrase', 'Hi <strong>{email}</strong>! Maaf forgot password gagal,<br/>silahkan menghubungi support kami untuk informasi lebih lanjut.', array(
							'{email}'=>$secret->email,
						));
						$render = 1;
					} else {
						if($key->delete()) {
							Yii::app()->params['reset_user_id'] = $secret->user_id;
						}
						$model = Users::model()->findByPk(Yii::app()->params['reset_user_id'], array(
							'select' => 'user_id, email, displayname, photos',
						));
						$title = Yii::t('phrase', 'Create a new password');
						$desc = Yii::t('phrase', 'Create a new password which you will easily remember!');
						$render = 2;
					}
				} else {
					$title = Yii::t('phrase', 'Forgot Password Not Valid');
					$desc = Yii::t('phrase', 'Maaf forgot password gagal,<br/>silahkan menghubungi support kami untuk informasi lebih lanjut.');
				}
			} else {
				$title = Yii::t('phrase', 'Forgot Password Not Valid');
				$desc = Yii::t('phrase', 'Maaf forgot password gagal,<br/>silahkan menghubungi support kami untuk informasi lebih lanjut.');
			}

		} else {
			$title = Yii::t('phrase', 'Forgot Password Not Valid');
			$desc = Yii::t('phrase', 'Maaf forgot password gagal,<br/>silahkan menghubungi support kami untuk informasi lebih lanjut.');
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->createUrl('site/index');
		
		$this->pageTitle = $title;
		$this->pageDescription = $desc;
		$this->pageMeta = '';
		$this->render('front_code', array(
			'model'=>$model,
			'render'=>$render,
		));		
		
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionPost($id) 
	{
		$model = Users::model()->findByPk($id, array(
			'select' => 'user_id, email, salt, password, displayname, verified',
		));

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Users'])) {
			$model->attributes=$_POST['Users'];
			$model->scenario = 'resetpassword';

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(isset($_GET['enablesave']) && $_GET['enablesave'] == 1) {
					if($model->save()) {						
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('get',array('name'=>urlencode($model->displayname), 'email'=>$model->email, 'type'=>'success')),
						));
					} else {
						print_r($model->getErrors());
					}
				}
			}
			Yii::app()->end();

		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionGet() 
	{
		$model=new UserForgot;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['UserForgot'])) {
			$model->attributes=$_POST['UserForgot'];
			$model->scenario = 'get';

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(isset($_GET['enablesave']) && $_GET['enablesave'] == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('get',array('name'=>urlencode($model->user->displayname), 'email'=>$model->user->email)),
						));
					} else {
						print_r($model->getErrors());
					}
				}
			}
			Yii::app()->end();

		}
		
		if(isset($_GET['name']) && isset($_GET['email'])) {
			if(isset($_GET['type']) && $_GET['type'] == 'success')
				$desc = Yii::t('phrase', 'You have successfully changed your password. To sign in to your account, use your email and new password at the following link:');			
			else {
				$desc = Yii::t('phrase', 'Hi, <strong>{name}</strong> an email with instructions for creating a new password has been sent to <strong>{email}</strong>', array(
					'{name}'=>$_GET['name'],
					'{email}'=>$_GET['email'],
				));
			}
		} else
			$desc = Yii::t('phrase', 'Enter your email address and we\'ll send you instructions to create a new password.<br/> Make sure you enter the same email address you used to create your profile');

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->createUrl('site/index');
		
		$this->pageTitle = Yii::t('phrase', 'Forgot your password?');
		$this->pageDescription = $desc;
		$this->pageMeta = '';
		$this->render('front_get',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = UserForgot::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-forgot-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
