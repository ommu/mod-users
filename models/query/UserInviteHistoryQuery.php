<?php
/**
 * UserInviteHistoryQuery
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserInviteHistory]].
 * @see \ommu\users\models\UserInviteHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 7 May 2018, 07:06 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserInviteHistoryQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\UserInviteHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\UserInviteHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
