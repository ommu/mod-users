<?php 
/**
 * User Newsletter (user-newsletter)
 * @var $this NewsletterController
 * @var $model UserNewsletter
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Support Newsletters'=>array('manage'),
		'Unsubscribe',
	);
?>

<?php 
if(isset($_GET['success']) || (isset($_GET['email']) || isset($_GET['secret']))) {
	if($renderError == 1)
		echo '<a class="button" href="'.Yii::app()->controller->createUrl('contact/index').'" title="'.Yii::t('phrase', 'Feedback').'">'.Yii::t('phrase', 'Feedback').'</a>';
} else {
	echo $this->renderPartial('_form', array(
		'model'=>$model,
		'launch'=>$launch,
	));
}?>