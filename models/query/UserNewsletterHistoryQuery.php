<?php
/**
 * UserNewsletterHistoryQuery
 *
 * This is the ActiveQuery class for [[\ommu\users\models\UserNewsletterHistory]].
 * @see \ommu\users\models\UserNewsletterHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 7 May 2018, 07:38 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\query;

class UserNewsletterHistoryQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\UserNewsletterHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\UserNewsletterHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
