<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul user.
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 28 March 2019, 14:41 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users;

use Yii;
use ommu\users\models\Users;

class Events extends \yii\base\BaseObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function onAfterCreateUsers($event)
	{
		self::setAssignmentRole($event);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function onAfterDeleteUsers($event)
	{
		$user = $event->sender;

		Yii::$app->authManager->revokeAll($user->user_id);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setAssignmentRole($event)
	{
		$user = $event->sender;

		$levelRoles = $user->level->assignment_roles;
		if(!empty($levelRoles)) {
			$manager = Yii::$app->authManager;
			foreach ($levelRoles as $val) {
				if($manager->getAssignment($val, $user->user_id) == null) {
					$item = $manager->getRole($val);
					$manager->assign($item, $user->user_id);
				}
			}
		}
	}
}