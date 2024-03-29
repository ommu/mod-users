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
 *	Subscribe
 *	Unsubscribe
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
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
		$arrThemes = $this->currentTemplate('public');
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
				'actions'=>array('index','subscribe','unsubscribe'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
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
		$this->redirect(array('subscribe'));
	}

	/**
	 * Lists all models.
	 */
	public function actionSubscribe() 
	{
		$model=new UserNewsletter;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['UserNewsletter'])) {
			$model->attributes=$_POST['UserNewsletter'];

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;
			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						if($model->user_id == 0) {
							$get = Yii::app()->controller->createUrl('subscribe', array('name'=>$model->email, 'email'=>$model->email));
						} else {
							$get = Yii::app()->controller->createUrl('subscribe', array('name'=>$model->user->displayname, 'email'=>$model->user->email));
						}
						echo CJSON::encode(array(
							'type' => 5,
							'get' => $get,
						));
					} else {
						print_r($model->getErrors());
					}
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->createUrl('site/index');
		
		$this->dialogFixed = true;
		$setting = OmmuSettings::model()->findByPk(1, array(
			'select' => 'id, online',
		));

		if($setting->view->online == 0) {
			$launch = 0;
			$title = (Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) ? Yii::t('phrase', 'You will be notified when we launch. Thank You!') : Yii::t('phrase', 'We will be back soon!');
			$desc = (Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) ? '' : Yii::t('phrase', 'Enter your email to be notified when more info is available.');
		} else {
			$launch = 1;
			$this->dialogFixedClosed=array(
				Yii::t('phrase', 'Create Your Account')=>Yii::app()->createUrl('users/signup/index'),
			);
			$title = (Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) ? Yii::t('phrase', 'Thanks for your subscription') : Yii::t('phrase', 'Newsletter');
			$desc = (Yii::app()->getRequest()->getParam('name') && Yii::app()->getRequest()->getParam('email')) ? '' : Yii::t('phrase', 'Subscribe and we\'ll keep you updated');
		}
		
		$this->pageTitle = $title;
		$this->pageDescription = $desc;
		$this->pageMeta = '';
		$this->render('front_subscribe', array(
			'model'=>$model,
			'launch'=>$launch,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionUnsubscribe() 
	{
		/**
		 * example get link
		 * http://localhost/_product/nirwasita_hijab/support/newsletter/unsubscribe/email/putra@ommu.co/secret/uvijijxykmabhiijdehinofgtuuvbcGH
		 * secret = salt[Users]
		 * email = email[Users]
		 */
		Yii::import('ext.phpmailer.Mailer');
		 
		 $renderError = 0;
		 if(isset($_GET['success']) || (Yii::app()->getRequest()->getParam('email') || isset($_GET['secret']))) {
			if(isset($_GET['success'])) {
				if(isset($_GET['date'])) {
					$title = Yii::t('phrase', 'Unsubscribe successful');
					$desc = Yii::t('phrase', 'Your email <strong>{email}</strong> has been successfully unsubscribed on {date}.', array(
						'{email}'=>Yii::app()->getRequest()->getParam('email'), 
						'{date}'=>$this->dateFormat($_GET['date'], 'long', false),
					));

				} else {
					$title = Yii::t('phrase', 'Unsubscribe Ticket Sent');
					$desc = Yii::t('phrase', 'Hi, instructions and ticket for unsubscribe newsletter has been sent to <strong>{email}</strong>', array(
						'{email}'=>Yii::app()->getRequest()->getParam('email'), 
					));
				}
				
			} else {
				if(Yii::app()->getRequest()->getParam('email') || isset($_GET['secret'])) {
					$newsletter = UserNewsletter::model()->findByAttributes(array('email' => Yii::app()->getRequest()->getParam('email')), array(
						'select' => 'newsletter_id, status, user_id, email, subscribe_date, updated_date',
					));
					if($newsletter != null) {
						if($newsletter->user_id != 0) {
							$secret = Users::model()->findByAttributes(array('salt' => $_GET['secret']), array(
								'select' => 'email',
							));
							$guest = ($secret != null && $secret->email == $newsletter->email) ? 1 : 0;
						} else {
							$guest = (md5($newsletter->email.$newsletter->subscribe_date) == $_GET['secret']) ? 1 : 0;
						}
						if($guest == 1) {
							if($newsletter->status == 1) {
								$newsletter->status = 0;
								if($newsletter->update()) {
									$title = Yii::t('phrase', 'Unsubscribe successful');
									$desc = Yii::t('phrase', 'Your email <strong>{email}</strong> has been successfully unsubscribed.', array(
										'{email}'=>$newsletter->email,
									));
								}
							} else {
								$title = Yii::t('phrase', 'Unsubscribe successful');
								$desc = Yii::t('phrase', 'Your email <strong>{email}</strong> has been successfully unsubscribed on {date}.', array(
									'{email}'=>$newsletter->email, 
									'{date}'=>$this->dateFormat($newsletter->updated_date, 'long', false),
								));
							}
						} else {
							$renderError = 1;
							$title = Yii::t('phrase', 'Unsubscribe Not Valid');
							$desc = Yii::t('phrase', 'Hi <strong>{email}</strong> maaf anda tidak bisa melakukan Unsubscribe,<br/>silahkan menghubungi support kami untuk informasi lebih lanjut.', array(
								'{email}'=>$newsletter->email,
							));
						}
					} else {
						$renderError = 1;
						$title = Yii::t('phrase', 'Unsubscribe Not Valid');
						$desc = Yii::t('phrase', 'Maaf anda tidak bisa melakukan Unsubscribe,<br/>silahkan menghubungi support kami untuk informasi lebih lanjut.');
					}
				}
			}
			
		} else {
			$model=new UserNewsletter;

			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['UserNewsletter'])) {
				$model->attributes=$_POST['UserNewsletter'];

				$jsonError = CActiveForm::validate($model);
				if(strlen($jsonError) > 2) {
					echo $jsonError;
					
				} else {
					if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
						if($model->validate()) {
							if($model->status == 1) {
								if($model->user_id != 0) {
									$email = $model->user->email;
									$displayname = $model->user->displayname;
									$secret = $model->user->salt;
								} else {
									$email = $displayname = $model->email;
									$secret = md5($email.$model->subscribe_date);
								}
								// Send Email to Member
								$ticket = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->createUrl('users/newsletter/unsubscribe', array('email'=>$email,'secret'=>$secret));
								Mailer::send($email, $displayname, 'Unsubscribe Ticket', $ticket);
								
								$url = Yii::app()->controller->createUrl('unsubscribe', array('success'=>$email));
							
							} else
								$url = Yii::app()->controller->createUrl('unsubscribe', array('success'=>$model->email, 'date'=>$model->updated_date));
							
							echo CJSON::encode(array(
								'type' => 5,
								'get' => $url,
							));
						
						} else {
							print_r($model->getErrors());
						}
					}
				}
				Yii::app()->end();
			}
			
			$title = Yii::t('phrase', 'Unsubscribe newsletter');
			$desc = Yii::t('phrase', 'Untuk unsubscribe newsletter, silahkan masukkan alamat email untuk mendapatkan instruksi dan ticket.');
		}
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->createUrl('site/index');
		$this->dialogFixed = true;
	
		$this->pageTitle = $title;
		$this->pageDescription = $desc;
		$this->pageMeta = '';
		$this->render('front_unsubscribe', array(
			'model'=>$model,
			'renderError'=>$renderError,
			'launch'=>2,
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='support-newsletter-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
