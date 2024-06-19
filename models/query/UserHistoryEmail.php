<?php
/**
 * UserHistoryEmail
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserHistoryEmail]].
 * @see \ommu\users\models\UserHistoryEmail
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 12 November 2018, 23:52 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserHistoryEmail extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserHistoryEmail[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserHistoryEmail|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
