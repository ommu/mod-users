<?php
/**
 * InviteController
 * @var $this InviteController
 * @var $model UserInvites
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	View
 *	Delete
 *	Runaction
 *	Publish
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 09:36 WIB
 * @link https://github.com/ommu/mod-users
 *
 *----------------------------------------------------------------------------------------------------------
 */

class InviteController extends Controller
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
				'actions'=>array('index','manage','add','view','delete','runaction','publish'),
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
	public function actionManage($user=null) 
	{
		$model=new UserInvites('search');
		$model->unsetAttributes();	// clear any default values
		$UserInvites = Yii::app()->getRequest()->getParam('UserInvites');
		if($UserInvites)
			$model->attributes=$UserInvites;

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$pageTitle = Yii::t('phrase', 'User Invites');
		if($user != null) {
			$data = Users::model()->findByPk($user);
			$pageTitle = Yii::t('phrase', 'User Invite: by {user_displayname}', array ('{user_displayname}'=>$data->displayname));
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new UserInvites;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['UserInvites'])) {
			$model->attributes=$_POST['UserInvites'];

			$email_i = $this->formatFileType($model->email_i);
			$result = array();

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->validate()) {
						$user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
						if(count($email_i) > 1) {
							foreach ($email_i as $email) {
								$condition = UserInvites::insertInvite($email, $user_id);
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
								'id' => 'partial-user-invites',
								'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User invite success created.<br/>{result}', array('{result}'=>$this->formatFileType($result, false, '<br/>'))).'</strong></div>',
							));
	
						} else {
							if(UserInvites::insertInvite($model->email_i, $user_id) == 1) {
								echo CJSON::encode(array(
									'type' => 5,
									'get' => Yii::app()->controller->createUrl('manage'),
									'id' => 'partial-user-invites',
									'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User invite success created.').'</strong></div>',
								));
							}
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

		$this->pageTitle = Yii::t('phrase', 'Create Invite');
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
		
		$pageTitle = Yii::t('phrase', 'Detail Invite: {newsletter_email} by Guest', array('{newsletter_email}'=>$model->newsletter->email));
		if($model->user_id)
			$pageTitle = Yii::t('phrase', 'Detail Invite: {newsletter_email} by {inviter_displayname}', array('{newsletter_email}'=>$model->newsletter->email, '{inviter_displayname}'=>$model->user->displayname));

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
			$model->publish = 2;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-user-invites',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User invite success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$pageTitle = Yii::t('phrase', 'Delete Invite: {newsletter_email} by Guest', array('{newsletter_email}'=>$model->newsletter->email));
		if($model->user_id)
			$pageTitle = Yii::t('phrase', 'Delete Invite: {newsletter_email} by {inviter_displayname}', array('{newsletter_email}'=>$model->newsletter->email, '{inviter_displayname}'=>$model->user->displayname));
		
		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunaction() 
	{
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = Yii::app()->getRequest()->getParam('action');

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('invite_id', $id);

			if($actions == 'publish') {
				UserInvites::model()->updateAll(array(
					'publish' => 1,
				), $criteria);
			} elseif($actions == 'unpublish') {
				UserInvites::model()->updateAll(array(
					'publish' => 0,
				), $criteria);
			} elseif($actions == 'trash') {
				UserInvites::model()->updateAll(array(
					'publish' => 2,
				), $criteria);
			} elseif($actions == 'delete') {
				UserInvites::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!Yii::app()->getRequest()->getParam('ajax'))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
	}

	/**
	 * Publish a particular model.
	 * If publish is successful, the browser will be redirected to the 'manage' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		$title = $model->publish == 1 ? Yii::t('phrase', 'Unpublish') : Yii::t('phrase', 'Publish');
		$replace = $model->publish == 1 ? 0 : 1;

		if(Yii::app()->request->isPostRequest) {
			// we only allow publish via POST request
			$model->publish = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-user-invites',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'User invite success updated.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$pageTitle = Yii::t('phrase', '{title} Invite: {newsletter_email} by Guest', array('{title}'=>$title, '{newsletter_email}'=>$model->newsletter->email));
		if($model->user_id)
			$pageTitle = Yii::t('phrase', '{title} Invite: {newsletter_email} by {inviter_displayname}', array('{title}'=>$title, '{newsletter_email}'=>$model->newsletter->email, '{inviter_displayname}'=>$model->user->displayname));

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_publish', array(
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
		$model = UserInvites::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-invites-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
