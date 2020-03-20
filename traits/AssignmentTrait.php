<?php
/**
 * AssignmentTrait
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 26 November 2019, 06:15 WIB
 * @link https://github.com/ommu/mod-users
 *
 * Contains many function that most used :
 *	buildJwtTokenFromClaim
 *
 */

namespace ommu\users\traits;

use Yii;

trait AssignmentTrait
{
	/**
	 * {@inheritdoc}
	 */
	public function setAssignmentRole($user)
	{
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

	/**
	 * {@inheritdoc}
	 */
	public function revokeAssignmentRole($user)
	{
		Yii::$app->authManager->revokeAll($user->user_id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function changeAssignmentRoleWithLevel($user)
	{
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
}