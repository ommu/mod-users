<?php
/**
 * SubscribeController
 * @var $this SubscribeController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	View
 *	Delete
 *	Status
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 12:25 WIB
 * @link https://github.com/ommu/mod-users
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SubscribeController extends Controller
{
	use FileTrait;
	
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
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = $this->currentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','manage','add','view','delete','status'),
				'users'=>array('@'),
				'expression'=>'in_array(Yii::app()->user->level, array(1,2))',
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
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage() 
	{
		$model=new UserNewsletter('search');
		$model->unsetAttributes();	// clear any default values
		$UserNewsletter = Yii::app()->getRequest()->getParam('UserNewsletter');
		if($UserNewsletter)
			$model->attributes=$UserNewsletter;

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$this->pageTitle = Yii::t('phrase', 'User Newsletters');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new UserNewsletter;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['UserNewsletter'])) {
			$model->attributes=$_POST['UserNewsletter'];

			$email_i = $this->formatFileType($model->email_i);
			$result = array();

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->validate()) {
						if(count($email_i) > 1) {
							foreach ($email_i as $email) {
								$condition = UserNewsletter::insertNewsletter($email);
								if($condition == 0)
									$result[] = Yii::t('phrase', '{email} (skip)', array('{email}'=>$email));
								else if($condition == 1)
									$result[] = Yii::t('phrase', '{email} (success)', array('{email}'=>$email));
								else if($condition == 2)
									$result[] = Yii::t('phrase', '{email} (error)', array('{email}'=>$email));
							}
							echo CJSON::encode(array(
								'type' => 5,
								'get' => Yii::app()->controller->createUrl('manage'),
								'id' => 'partial-support-newsletter',
								'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User newsletter success created.<br/>{result}', array('{result}'=>$this->formatFileType($result, false, '<br/>'))).'</strong></div>',
							));
	
						} else {
							if($model->save()) {
								echo CJSON::encode(array(
									'type' => 5,
									'get' => Yii::app()->controller->createUrl('manage'),
									'id' => 'partial-user-newsletter',
									'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User newsletter success created.').'</strong></div>',
								));
							} else
								print_r($model->getErrors());
						}
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Create Newsletter');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add', array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Detail Newsletter: {email}', array('{email}'=>$model->email));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if($model->delete()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-user-newsletter',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User newsletter success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Newsletter: {email}', array('{email}'=>$model->email));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Status a particular model.
	 * If status is successful, the browser will be redirected to the 'manage' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionStatus($id) 
	{
		$model=$this->loadModel($id);
		$title = $model->status == 1 ? Yii::t('phrase', 'Unsubscribe') : Yii::t('phrase', 'Subscribe');
		$replace = $model->status == 1 ? 0 : 1;

		if(Yii::app()->request->isPostRequest) {
			// we only allow status via POST request
			$model->status = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-user-newsletter',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User newsletter success updated.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', '{title} Newsletter: {email}', array('{title}'=>$title, '{email}'=>$model->email));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_status', array(
			'title'=>$title,
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
		$model = UserNewsletter::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-newsletter-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
