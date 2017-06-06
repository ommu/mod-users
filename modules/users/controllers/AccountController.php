<?php
/**
 * AccountController
 * @var $this AccountController
 * @var $model Users
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Login
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

class AccountController extends /*SBaseController*/ Controller
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
				'actions'=>array('index','login'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
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
		$this->redirect(Yii::app()->controller->createUrl('login'));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->createUrl('site/index'));

		} else {				
			$model=new LoginForm;

			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				if(!isset($_GET['email']))
					$model->scenario = 'loginemail';
				else
					$model->scenario = 'loginpassword';

				$jsonError = CActiveForm::validate($model);
				if(strlen($jsonError) > 2) {
					echo $jsonError;

				} else {
					if(isset($_GET['enablesave']) && $_GET['enablesave'] == 1) {
						if(!isset($_GET['email'])) {
							if($model->validate()) {
								echo CJSON::encode(array(
									'type' => 5,
									'get' => Yii::app()->controller->createUrl('login', array('email'=>$model->email)),
								));
							} else {
								print_r($model->getErrors());
							}
						} else {
							// validate user input and redirect to the previous page if valid
							if($model->validate() && $model->login()) {
								Users::model()->updateByPk(Yii::app()->user->id, array(
									'lastlogin_date'=>date('Y-m-d H:i:s'), 
									'lastlogin_ip'=>$_SERVER['REMOTE_ADDR'],
									'lastlogin_from'=>Yii::app()->params['product_access_system'],
								));
								
								echo CJSON::encode(array(
									'redirect' => Yii::app()->user->returnUrl,
								));
							} else {
								print_r($model->getErrors());
							}
						}
					}
				}
				Yii::app()->end();
				
			}
			
			// display the login form
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->createUrl('site/index');

			$this->dialogFixed = true;
			if(!isset($_GET['email'])) {
				$this->dialogFixedClosed=array(
					Yii::t('phrase', 'Create Your Account')=>Yii::app()->createUrl('users/signup/index'),
				);
			} else {
				$this->dialogFixedClosed=array(
					Yii::t('phrase', 'Forgot your password')=>Yii::app()->createUrl('users/forgot/get'),
				);
			}		
			
			$this->pageTitle = Yii::t('phrase', 'Login');
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('front_login',array(
				'model'=>$model,
			));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
