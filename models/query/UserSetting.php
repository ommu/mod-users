<?php
/**
 * UserSetting
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserSetting]].
 * @see \ommu\users\models\UserSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 8 November 2018, 12:39 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserSetting extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserSetting[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserSetting|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
