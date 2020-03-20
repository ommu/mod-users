<?php
/**
 * UserNewsletter
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserNewsletter]].
 * @see \ommu\users\models\UserNewsletter
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 May 2018, 13:33 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserNewsletter extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserNewsletter[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\UserNewsletter|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
