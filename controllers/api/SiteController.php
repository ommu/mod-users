<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Users
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Error
 *	Index
 *	Login
 *	Logout
 *
 *	LoadModel
 *	performAjaxValidation
 *	Test
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Users
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends ControllerApi
{
	/**
	 * Initialize public template
	 */
	public function init() 
	{
		Yii::import('application.modules.users.models.*');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->redirect(Yii::app()->createUrl('site/index'));
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->redirect(Yii::app()->createUrl('site/index'));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(Yii::app()->request->isPostRequest) {
			$token = trim($_POST['token']);
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);
			
			if(isset($_POST['token']))
				$userToken = ViewUsers::model()->findByAttributes(array('token_oauth'=>$token));
			
			$email = isset($_POST['token']) ? $userToken->user_relation->email : $email;
			$password = isset($_POST['token']) ? null : $password;
			
			if(preg_match('/@/',$email)) //$this->username can filled by username or email 
				$record = Users::model()->findByAttributes(array('email' => $email));
			else
				$record = Users::model()->findByAttributes(array('username' => $email));
			
			$logindate = date('Y-m-d H:i:s');
			$return = '';
			if($record === null || (!isset($_POST['token']) && (!isset($_POST['email']) || isset($_POST['email']) && $email == ''))) {
				$return['success'] = '0';
				$return['error'] = 'USER_NULL';
				$return['message'] = Yii::t('phrase', 'error, user tidak ditemukan');
				
			} else if(!isset($_POST['token']) && (($record->password !== Users::hashPassword($record->salt,$password)) || (!isset($_POST['password']) || isset($_POST['password']) && $password == ''))) {
				$return['success'] = '0';
				$return['error'] = 'USER_WRONG_PASSWORD';
				$return['message'] = Yii::t('phrase', 'error, password salah');
				
			} else {
				if(isset($_POST['token']) && $userToken == null) {
					$return['success'] = '0';
					$return['error'] = 'USER_WRONG_TOKEN';
					$return['message'] = Yii::t('phrase', 'error, user tidak ditemukan');
					
				} else {
					if($record->enabled == 1) {
						$return['success'] = '1';
						$return['message'] = 'success';
						$return['token'] = $record->view->token_password;
						$return['oauth'] = $record->view->token_oauth;
						$return['userlevel_id'] = $record->level_id;
						$return['userlevel'] = $record->view_user->level_name;
						$return['email'] = $record->email;
						$return['username'] = $record->username;
						$return['first_name'] = $record->first_name;
						$return['last_name'] = $record->last_name;
						$return['displayname'] = $record->displayname;
						$return['photo'] = $record->photos != '' ? Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl.'/public/users/'.$record->user_id.'/'.$record->photos : '';
						$return['lastlogin_date'] = $logindate;
						$return['password'] = md5(md5($record->salt.$record->password).$logindate);
						$return['enabled'] = $record->enabled;
						$return['verified'] = $record->verified;
						$return['secretkey'] = $record->salt;
						if(isset($_POST['access'])) {
							Users::model()->updateByPk($record->user_id, array(
								'lastlogin_date'=>$logindate, 
								'lastlogin_ip'=>isset($_POST['ipaddress']) ? $_POST['ipaddress'] : $_SERVER['REMOTE_ADDR'],
								'lastlogin_from'=>isset($_POST['token']) ? '@'.$_POST['access'] : $_POST['access'],
							));
						}
					} else {
						$return['success'] = '0';
						$return['error'] = 'USER_BLOCK';
						$return['message'] = Yii::t('phrase', 'error, user tidak bisa digunakan');					
					}
				}
			}
			$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));
			
		} else
			$this->redirect(Yii::app()->createUrl('site/index'));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
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

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionTest()
	{
		$url = 'http://localhost/_client_bpadjogja_20150804/users/api/site/login/email/putra.sudaryanto@gmail.com/password/0o9i8u7y';
		$json = file_get_contents($url);
		$onject = json_decode($json);
		print_r($onject);
	}
}