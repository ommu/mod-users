<?php
/**
 * @var $this yii\web\View
 * @var $this ommu\users\controllers\PasswordController
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 30 May 2018, 11:27 WIB
 * @link http://opensource.ommu.co
 *
 */

use Yii;
use yii\helpers\Html;
?>

<?php if(Yii::$app->session->hasFlash('warning'))
	echo Yii::$app->session->getFlash('warning');?>

<p>
	You may change the content of this page by modifying
	the file <code><?php echo __FILE__; ?></code>.
</p>