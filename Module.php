<?php
/**
 * users module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 05:38 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users;

use Yii;

class Module extends \app\components\Module
{
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'ommu\users\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();
	}
}
