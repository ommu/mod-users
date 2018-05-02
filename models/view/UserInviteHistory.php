<?php
/**
 * UserInviteHistory
 * version: 0.0.1
 *
 * This is the model class for table "_view_user_invite_history".
 *
 * The followings are the available columns in table "_view_user_invite_history":
 * @property integer $id
 * @property integer $expired
 * @property integer $invite_id
 * @property integer $verify_day_left
 * @property integer $verify_hour_left

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 23 October 2017, 09:38 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models\view;

use Yii;
use yii\helpers\Url;

class UserInviteHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_user_invite_history';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['id'];
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'expired', 'invite_id', 'verify_day_left', 'verify_hour_left'], 'integer'],
			[['invite_id'], 'required'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'expired' => Yii::t('app', 'Expired'),
			'invite_id' => Yii::t('app', 'Invite'),
			'verify_day_left' => Yii::t('app', 'Verify Day Left'),
			'verify_hour_left' => Yii::t('app', 'Verify Hour Left'),
		];
	}
	
	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['id'] = 'id';
		$this->templateColumns['expired'] = 'expired';
		$this->templateColumns['invite_id'] = 'invite_id';
		$this->templateColumns['verify_day_left'] = 'verify_day_left';
		$this->templateColumns['verify_hour_left'] = 'verify_hour_left';
	}

}
