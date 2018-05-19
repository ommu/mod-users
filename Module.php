<?php
/**
 * users module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 8 October 2017, 05:38 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\users;

class Module extends \app\components\Module
{
	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'ommu\users\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
