<?php
/**
 * User Verifies (user-verify)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\o\VerifyController
 * @var $model ommu\users\models\UserVerify
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 17 October 2017, 15:00 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Verifies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="user-verify-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
