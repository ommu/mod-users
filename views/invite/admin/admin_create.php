<?php
/**
 * User Invites (user-invites)
 * @var $this app\components\View
 * @var $this ommu\users\controllers\invite\AdminController
 * @var $model ommu\users\models\UserInvites
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:27 WIB
 * @modified date 13 November 2018, 13:27 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="user-invites-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
