<?php
/**
* SiteController
* Handle SiteController
* Copyright (c) 2013, Ommu Platform (ommu.co). All rights reserved.
* version: 2.0.0
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
*
* @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
* @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
* @link http://company.ommu.co
* @contact (+62)856-299-4114
*
*----------------------------------------------------------------------------------------------------------
*/

class SiteController extends Controller
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
		$this->redirect(Yii::app()->createUrl('admin/index'));
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->redirect(Yii::app()->createUrl('admin/index'));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(Yii::app()->request->isPostRequest) {
			if(isset($_POST['token']))
				$userToken = UserOption::model()->findByAttributes(array('secret_key'=>$_POST['token']));
			
			$email = isset($_POST['token']) ? $userToken->user->email : $_POST['email'];
			$password = isset($_POST['token']) ? null : $_POST['password'];
			
			if(preg_match('/@/',$email)) //$this->username can filled by username or email 
				$record = Users::model()->findByAttributes(array('email' => $email));
			else
				$record = Users::model()->findByAttributes(array('username' => $email));
			
			$logindate = date('Y-m-d H:i:s');
			$return = '';
			if($record === null || (!isset($_POST['token']) && (!isset($_POST['email']) || isset($_POST['email']) && $_POST['email'] == ''))) {
				$return['success'] = '0';
				$return['message'] = 'error, user tidak ada';
				
			} else if(!isset($_POST['token']) && (($record->password !== Users::hashPassword($record->salt,$_POST['password'])) || (!isset($_POST['password']) || isset($_POST['password']) && $_POST['password'] == ''))) {
				$return['success'] = '0';
				$return['message'] = 'error, password salah';
				
			} else {
				if(isset($_POST['token']) && $userToken == null) {
					$return['success'] = '0';
					$return['message'] = 'error, password salah';
					
				} else {
					$return['success'] = '1';
					$return['message'] = 'success';
					$return['id'] = $record->user_id;
					$return['username'] = $record->username;
					$return['email'] = $record->email;
					$return['displayname'] = $record->displayname;
					$return['userlevel_id'] = $record->level->level_id;
					$return['userlevel'] = Phrase::trans($record->level->name,2);
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
				}
			}
			echo CJSON::encode($return);
			
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