<?php
/**
 * UserInviteHistoryQuery
 *
 * This is the ActiveQuery class for [[\app\modules\user\models\UserInviteHistory]].
 * @see \app\modules\user\models\UserInviteHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 7 May 2018, 07:06 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\user\models\query;

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
	 * @return \app\modules\user\models\UserInviteHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\user\models\UserInviteHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
