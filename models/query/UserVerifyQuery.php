<?php
/**
 * UserVerifyQuery
 *
 * This is the ActiveQuery class for [[\app\modules\user\models\UserVerify]].
 * @see \app\modules\user\models\UserVerify
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 2 May 2018, 16:27 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\user\models\query;

class UserVerifyQuery extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * @inheritdoc
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\user\models\UserVerify[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\user\models\UserVerify|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
