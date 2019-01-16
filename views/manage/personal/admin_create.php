<?php
/**
 * Users (users)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\manage\PersonalController
 * @var $model ommu\users\models\Users
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 15 November 2018, 07:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;
use app\models\CoreSettings;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
];

$setting = CoreSettings::find()
	->select(['signup_approve', 'signup_verifyemail', 'signup_random'])
	->where(['id' => 1])
	->one();
?>

<div class="users-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
]); ?>

</div>
