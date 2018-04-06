<?php
/**
 * MemberController
 * @var $this MemberController
 * @var $model Users
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	ChangePassword
 *	CheckPassword
 *	CheckOauth
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 29 March 2016, 23:16 WIB
 * @link https://github.com/ommu/mod-users
 *
 *----------------------------------------------------------------------------------------------------------
 */

class MemberController extends ControllerApi
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

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
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionChangePassword() 
	{
		if(Yii::app()->request->isPostRequest) {
			$token = trim($_POST['token']);
			$password = trim($_POST['password']);
			$newpassword = trim($_POST['newpassword']);
			$confirmpassword = trim($_POST['confirmpassword']);
			
			$user = ViewUsers::model()->findByAttributes(array('token_password' => $token));
			if($user != null) {
				$model = Users::model()->findByPk(array('user_id' => $user->user_id));
				$model->scenario = 'formChangePassword';
				$model->oldPassword = $password;	
				$model->newPassword = $newpassword;
				$model->confirmPassword = $confirmpassword;
				if($model->save()) {
					$return['success'] = '1';
					$return['message'] = 'success';					
				} else {
					$return['success'] = '0';
					$return['error'] = 'USER_PASSWORD_ERROR';
					$data = array();
					foreach($model->getErrors() as $key => $val) {
						foreach($val as $key => $row)
							$data[] = $row;
					}
					$return['message'] = implode(', ', $data);
				}
				
			} else {
				$return['success'] = '0';
				$return['error'] = 'NULL';
				$return['message'] = Yii::t('phrase', 'error, user tidak ditemukan');
			}
			$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));
			
		} else
			$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionCheckPassword() 
	{
		if(Yii::app()->request->isPostRequest) {
			$token = trim($_POST['token']);			
			$model = ViewUsers::model()->findByAttributes(array('token_password' => $token));
			
			if($model != null) {
				$return['success'] = '1';
				$return['error'] = 'NOTNULL';		
			} else {
				$return['success'] = '0';
				$return['error'] = 'NULL';
			}
			$this->_sendResponse(200, CJSON::encode($this->renderJson($return)));
			
		} else
			$this->redirect(Yii::app()->createUrl('site/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionCheckOauth() 
	{
		if(Yii::app()->request->isPostRequest) {
			$token = trim($_POST['token']);			
			$model = ViewUsers::model()->findByAttributes(array('token_oauth' => $token));
			
			if($model != null) {
				$return['success'] = '1';
				$return['error'] = 'NOTNULL';		
			} else {
				$return['success'] = '0';
				$return['error'] = 'NULL';
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
