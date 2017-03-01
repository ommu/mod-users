<?php
/**
* OauthController
* Handle OauthController
* version: 0.0.1
* Reference start
*
* TOC :
*	Error
*	Index
*	Login
*
*	LoadModel
*	performAjaxValidation
*
* @author Putra Sudaryanto <putra@sudaryanto.id>
* @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
* @link https://github.com/ommu/Users
* @contact (+62)856-299-4114
*
*----------------------------------------------------------------------------------------------------------
*/

class OauthController extends Controller
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
				$return['error'] = 'USER';
				$return['message'] = Yii::t('phrase', 'error, user tidak ditemukan');
				
			} else if(!isset($_POST['token']) && (($record->password !== Users::hashPassword($record->salt,$password)) || (!isset($_POST['password']) || isset($_POST['password']) && $password == ''))) {
				$return['success'] = '0';
				$return['error'] = 'PASSWORD';
				$return['message'] = Yii::t('phrase', 'error, password salah');
				
			} else {
				if(isset($_POST['token']) && $userToken == null) {
					$return['success'] = '0';
					$return['error'] = 'TOKEN';
					$return['message'] = Yii::t('phrase', 'error, user tidak ditemukan');
					
				} else {
					if($record->enabled == 1) {
						$return['success'] = '1';
						$return['message'] = 'success';
						$return['email'] = $record->email;
						$return['displayname'] = $record->displayname;
						//$return['username'] = $record->username;
						$return['photo'] = $record->photo_id != 0 ? Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl.'/public/users/'.$record->user_id.'/'.$record->photo->photo : '';
						/*
						$return['userlevel_id'] = $record->level_id;
						$return['userlevel'] = $record->view_user->level_name;
						$return['lastlogin_date'] = $logindate;
						$return['password'] = md5(md5($record->salt.$record->password).$logindate);
						$return['enabled'] = $record->enabled;
						$return['verified'] = $record->verified;
						$return['secretkey'] = $record->salt;
						*/
						if(isset($_POST['access'])) {
							Users::model()->updateByPk($record->user_id, array(
								'lastlogin_date'=>$logindate, 
								'lastlogin_ip'=>isset($_POST['ipaddress']) ? $_POST['ipaddress'] : $_SERVER['REMOTE_ADDR'],
								'lastlogin_from'=>isset($_POST['token']) ? '@'.$_POST['access'] : $_POST['access'],
							));
						}	
					} else {
						$return['success'] = '0';
						$return['error'] = 'USERBLOCK';
						$return['message'] = Yii::t('phrase', 'error, user tidak bisa digunakan');					
					}						
				}
			}
			echo CJSON::encode($return);
			
		} else
			$this->redirect(Yii::app()->createUrl('site/index'));
	}
}