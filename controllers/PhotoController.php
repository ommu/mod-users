<?php
/**
 * PhotoController
 * @var $this PhotoController
 * @var $model UserPhoto
 * @var $form CActiveForm
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	AjaxAdd
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class PhotoController extends Controller
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('ajaxadd'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxAdd() 
	{
		if(!Yii::app()->user->isGuest)
			$id = Yii::app()->user->id;
		else
			$id = Yii::app()->user->id;
		
		$user_path = "public/users/".$id;
		if(!file_exists($user_path)) {
			mkdir($user_path, 0755, true);

			// Add File in User Folder (index.php)
			$newFile = $user_path.'/index.php';
			$FileHandle = fopen($newFile, 'w');
		} else
			@chmod($user_path, 0755, true);
		
		$userPhoto = CUploadedFile::getInstanceByName('namaFile');
		$fileName	= time().'_'.$id.'.'.$userPhoto->extensionName;
		if($userPhoto->saveAs($user_path.'/'.$fileName)) {
			$model = new UserPhoto;
			$model->user_id = $id;
			$model->cover = '1';
			$model->photo = $fileName;			
			if($model->save()) {
				if(isset($_GET['type']) && $_GET['type'] == 'admin') {
					$images = Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/users/'.$model->user_id.'/'.$model->photo, 82, 82, 1);
					$path = 'div.account a.photo img';
				} else {
					$images = Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/users/'.$model->user_id.'/'.$model->photo, 200, 200, 1);
					$path = 'div.account a.photo img';
				}
					
				echo CJSON::encode(array(
					'id' => $path,
					'image' => $images,
				));
			}
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = UserPhoto::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-photo-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
