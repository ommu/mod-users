<?php
/**
 * SiteController
 * @var $this SiteController
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
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 29 March 2016, 23:16 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

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
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
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
		$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionLogin() 
	{
		if(Yii::app()->request->isPostRequest) {
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);
			
			$record = Users::model()->findByAttributes(array('email' => $email));
			
			$logindate = date('Y-m-d H:i:s');
			$return = '';
			if($record === null) {
				$return['success'] = '0';
				$return['error'] = 'USER_NULL';
				$return['message'] = 'error, user tidak ditemukan';
				
			} else if($record->password !== Users::hashPassword($record->salt,$password)) {
				$return['success'] = '0';
				$return['error'] = 'USER_WRONG_PASSWORD';
				$return['message'] = 'error, password salah';
				
			} else {
				if($record->enabled == 1) {
					$return['success'] = '1';
					$return['token'] = $record->view->token_password;
					$return['oauth'] = $record->view->token_oauth;
					$return['email'] = $record->email;
					$return['displayname'] = $record->displayname;
					$return['lastlogin_date'] = $logindate;
					$return['enabled'] = $record->enabled;
					$return['verified'] = $record->verified;
					Users::model()->updateByPk($record->user_id, array(
						'lastlogin_date'=>$logindate, 
						'lastlogin_ip'=>$_SERVER['REMOTE_ADDR'],
					));
					
				} else {
					$return['success'] = '0';
					$return['error'] = 'USER_BLOCK';
					$return['message'] = 'error, user tidak bisa digunakan';					
				}
			}
			echo CJSON::encode($return);
			
		} else
			$this->redirect(Yii::app()->createUrl('site/index'));
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
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
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
