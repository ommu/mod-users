<?php
/**
 * Users
 *
 * This is the ActiveQuery class for [[\ommu\users\models\Users]].
 * @see \ommu\users\models\Users
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 May 2018, 13:29 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class Users extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function suggest() 
	{
		return $this->select(['user_id', 'email', 'displayname', 'photos'])
			->andWhere(['enabled' => '1']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\Users[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\Users|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
