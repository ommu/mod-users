<?php
/**
 * User Settings (user-setting)
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\setting\AdminController
 * @var $model ommu\users\models\UserSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 9 October 2017, 11:22 WIB
 * @modified date 9 November 2018, 07:13 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\libraries\grid\GridView;
use yii\widgets\Pjax;
use app\components\menu\MenuContent;
use app\components\menu\MenuOption;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Setting'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<div class="user-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>