<?php
/**
 * User Levels (user-level)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\setting\LevelController
 * @var $model ommu\users\models\UserLevel
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 07:46 WIB
 * @modified date 4 May 2018, 09:02 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/admin/index']), 'icon' => 'table'],
];
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>