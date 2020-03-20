<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul user.
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 28 March 2019, 14:41 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users;

use Yii;

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
	public static function onAfterUpdateUsers($event)
	{
		$user = $event->sender;
		$manager = Yii::$app->authManager;

		$oldAssignmentRoles = $user->assignmentRoles;

		// update new assignment
		$levelRoles = $user->level->assignment_roles;
		if(!empty($levelRoles)) {
			foreach ($levelRoles as $val) {
				if($manager->getAssignment($val, $user->user_id) != null) {
					unset($oldAssignmentRoles[$val]);
					continue;
				}

				$item = $manager->getRole($val);
				$manager->assign($item, $user->user_id);
			}
		}

		// drop difference assignment
		if(!empty($oldAssignmentRoles)) {
			foreach ($oldAssignmentRoles as $val) {
				$item = $manager->getRole($val);
				$manager->revoke($item, $user->user_id);
			}
		}
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

		// insert new assignment
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