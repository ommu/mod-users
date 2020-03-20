<?php
/**
 * UserHistoryPassword
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserHistoryPassword]].
 * @see \ommu\users\models\UserHistoryPassword
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 12 November 2018, 23:53 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserHistoryPassword extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserHistoryPassword[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserHistoryPassword|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
