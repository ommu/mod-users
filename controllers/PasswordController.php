<?php
/**
 * PasswordController
 * @var $this yii\web\View
 *
 * Reference start
 * TOC :
 *	Reset
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 30 May 2018, 11:27 WIB
 * @link http://opensource.ommu.co
 *
 */

namespace ommu\users\controllers;

use Yii;
use app\components\Controller;

class PasswordController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function actionReset()
	{
		$this->view->title = 'PasswordControllers';
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('front_reset');
	}

}
