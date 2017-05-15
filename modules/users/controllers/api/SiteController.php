<?php
/**
 * SiteController
 * @var $this SiteController
 * @var $model Users
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Error
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
 * @contact (+62)856-299-4114
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
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
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
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
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
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			$access = trim($_POST['access']);
			$ipaddress = trim($_POST['ipaddress']);
			
			if(isset($_POST['token']))
				$userToken = ViewUsers::model()->findByAttributes(array('token_oauth'=>$token));
			
			$username = isset($_POST['token']) ? $userToken->user->email : $username;
			$password = isset($_POST['token']) ? null : $password;
			
			if(preg_match('/@/',$username)) //$this->username can filled by username or email 
				$record = Users::model()->findByAttributes(array('email' => $username));
			else
				$record = Users::model()->findByAttributes(array('username' => $username));
			
			$logindate = date('Y-m-d H:i:s');
			$return = '';
			
			if($record === null || (!isset($_POST['token']) && (!isset($_POST['username']) || isset($_POST['username']) && $username == ''))) {
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
						$return['userlevel'] = Phrase::trans($record->level->name);
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
								'lastlogin_ip'=>isset($_POST['ipaddress']) ? $ipaddress : $_SERVER['REMOTE_ADDR'],
								'lastlogin_from'=>isset($_POST['token']) ? '@'.$access : $access,
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
		if(Yii::app()->request->isPostRequest) {
			$token = trim($_POST['token']);
			
			if(isset($_POST['token']))
				$userToken = ViewUsers::model()->findByAttributes(array('token_oauth'=>$token));
			
			$username = $userToken->user->email;
			
			if(preg_match('/@/',$username)) //$this->username can filled by username or email 
				$record = Users::model()->findByAttributes(array('email' => $username));
			else
				$record = Users::model()->findByAttributes(array('username' => $username));
			
			$return = '';
			if($record === null) {
				$return['success'] = '0';
				$return['error'] = 'USER_NULL';
				$return['message'] = Yii::t('phrase', 'error, user tidak ditemukan');				
			} else {
				$return['success'] = '1';
				$return['message'] = 'success';
				Yii::app()->user->logout();
			}
			$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));
			
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