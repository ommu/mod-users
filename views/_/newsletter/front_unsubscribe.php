<?php 
/**
 * User Newsletter (user-newsletter)
 * @var $this SubscribeController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 */
 
	$this->breadcrumbs=array(
		'Support Newsletters'=>array('manage'),
		'Unsubscribe',
	);
?>

<?php 
if(Yii::app()->getRequest()->getParam('success') || (Yii::app()->getRequest()->getParam('email') || Yii::app()->getRequest()->getParam('secret'))) {
	if($renderError == 1)
		echo '<a class="button" href="'.Yii::app()->controller->createUrl('contact/index').'" title="'.Yii::t('phrase', 'Feedback').'">'.Yii::t('phrase', 'Feedback').'</a>';
} else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
		'launch'=>$launch,
	));
}?>