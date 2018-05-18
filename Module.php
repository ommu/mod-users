<?php

namespace ommu\users;

/**
 * user module definition class
 */
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
