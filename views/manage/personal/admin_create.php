<?php
/**
 * Users (users)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\manage\PersonalController
 * @var $model ommu\users\models\Users
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @modified date 15 November 2018, 07:04 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<div class="users-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
