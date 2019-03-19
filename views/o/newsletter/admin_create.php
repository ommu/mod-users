<?php
/**
 * User Newsletters (user-newsletter)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\NewsletterController
 * @var $model ommu\users\models\UserNewsletter
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 14 November 2018, 01:24 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="user-newsletter-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
