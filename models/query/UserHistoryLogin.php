<?php
/**
 * UserHistoryLogin
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserHistoryLogin]].
 * @see \ommu\users\models\UserHistoryLogin
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 12 November 2018, 23:53 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserHistoryLogin extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserHistoryLogin[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserHistoryLogin|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
