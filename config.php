<?php
/**
 * users module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 October 2017, 05:38 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use ommu\users\Events;
use ommu\users\models\Users;

return [
	'id' => 'users',
	'class' => ommu\users\Module::className(),
	'events' => [
		[
			'class'    => Users::className(),
			'event'    => Users::EVENT_AFTER_CREATE_USERS,
			'callback' => [Events::className(), 'onAfterCreateUsers']
		],
		[
			'class'    => Users::className(),
			'event'    => Users::EVENT_AFTER_DELETE_USERS,
			'callback' => [Events::className(), 'onAfterDeleteUsers']
		],
	],
];