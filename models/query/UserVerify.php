<?php
/**
 * UserVerify
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserVerify]].
 * @see \ommu\users\models\UserVerify
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 May 2018, 16:27 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserVerify extends \yii\db\ActiveQuery
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
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserVerify[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserVerify|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
