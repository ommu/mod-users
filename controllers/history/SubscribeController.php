<?php
/**
 * SubscribeController
 * @var $this SubscribeController
 * @var $model UserNewsletterHistory
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 06:42 WIB
 * @link https://github.com/ommu/mod-users
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SubscribeController extends Controller
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
		if(!Yii::app()->user->isGuest) {
			if(Yii::app()->user->level == 1) {
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
				'actions'=>array('index','manage','view','delete'),
				'users'=>array('@'),
				'expression'=>'Yii::app()->user->level == 1',
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
	public function actionManage($newsletter=null) 
	{
		$model=new UserNewsletterHistory('search');
		$model->unsetAttributes();	// clear any default values
		$UserNewsletterHistory = Yii::app()->getRequest()->getParam('UserNewsletterHistory');
		if($UserNewsletterHistory)
			$model->attributes=$UserNewsletterHistory;

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$pageTitle = Yii::t('phrase', 'User Newsletter History');
		if($newsletter != null) {
			$data = UserNewsletter::model()->findByPk($newsletter);
			$pageTitle = Yii::t('phrase', 'User Newsletter History: {newsletter_email}', array ('{newsletter_email}'=>$data->email));
			if($data->user_id)
				$pageTitle = Yii::t('phrase', 'User Newsletter History: {newsletter_displayname} ({newsletter_email})', array ('{newsletter_displayname}'=>$data->user->displayname, '{newsletter_email}'=>$data->user->email));
		}
		
		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
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
		
		$pageTitle = Yii::t('phrase', 'Detail Newsletter History: {newsletter_email}', array ('{newsletter_email}'=>$model->newsletter->email));
		if($model->newsletter->user_id)
			$pageTitle = Yii::t('phrase', 'Detail Newsletter History: {newsletter_displayname} ({newsletter_email})', array ('{newsletter_displayname}'=>$model->newsletter->user->displayname, '{newsletter_email}'=>$model->newsletter->user->email));

		$this->pageTitle = $pageTitle;
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
					'id' => 'partial-user-newsletter-history',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User newsletter history success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;
		
		$pageTitle = Yii::t('phrase', 'Delete Newsletter History: {newsletter_email}', array ('{newsletter_email}'=>$model->newsletter->email));
		if($model->newsletter->user_id)
			$pageTitle = Yii::t('phrase', 'Delete Newsletter History: {newsletter_displayname} ({newsletter_email})', array ('{newsletter_displayname}'=>$model->newsletter->user->displayname, '{newsletter_email}'=>$model->newsletter->user->email));

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = UserNewsletterHistory::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-newsletter-history-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
