<?php
/**
 * User Forgots (user-forgot)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\ForgotController
 * @var $model ommu\users\models\UserForgot
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 17 October 2017, 15:01 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forgots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="user-forgot-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
