<?php
/**
 * DeviceController
 * @var $this DeviceController
 * @var $model UserDevice
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Android
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 9 April 2016, 06:37 WIB
 * @link https://github.com/ommu/Users
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class DeviceController extends ControllerApi
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
	public function actionAndroid()
	{
		if(Yii::app()->request->isPostRequest) {
			$token = trim($_POST['token']);
			$android_key = trim($_POST['android_key']);
			
			$criteria=new CDbCriteria;
			$criteria->select = array('t.id','t.user_id');
			$criteria->compare('t.publish',1);
			$criteria->compare('t.android_id',$android_key);
			
			$device = UserDevice::model()->find($criteria);
			if($token) {
				$user = ViewUsers::model()->findByAttributes(array('token_password' => $token), array(
					'select' => 'user_id',
				));
			}
			if($device == null) {
				$data=new UserDevice;
				if($token && $user != null)
					$data->user_id = $user->user_id;
				$data->android_id = $android_key;
					
				if($data->save()) {
					$return = array(
						'success' => '1',
						'message' => Yii::t('phrase', 'success, device berhasil ditambahkan'),
					);
				} else {
					$return = array(
						'success' => '0',
						'message' => Yii::t('phrase', 'success, device tidak berhasil ditambahkan'),
					);
				}
			} else {
				$return['success'] = '1';				
				if($token && $device->user_id == 0) {
					if($user != null) {
						if(UserDevice::model()->updateByPk($device->id, array('user_id' => $user->user_id)))
							$return['message'] = Yii::t('phrase', 'success, device berhasil ditambahkan (info member selesai diperbarui)');
						else
							$return['message'] = Yii::t('phrase', 'success, device tidak terjadi perubahan (info member tidak ada perubahan)');
					} else
						$return['message'] = Yii::t('phrase', 'success, device tidak terjadi perubahan (member tidak ditemukan)');
				} else
					$return['message'] = Yii::t('phrase', 'success, device tidak terjadi perubahan');
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
		$model = UserDevice::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-device-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
