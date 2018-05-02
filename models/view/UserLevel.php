<?php
/**
 * UserLevel
 * version: 0.0.1
 *
 * This is the model class for table "_user_level".
 *
 * The followings are the available columns in table "_user_level":
 * @property integer $level_id
 * @property string $users
 * @property string $user_pending
 * @property string $user_noverified
 * @property string $user_blocked
 * @property integer $user_all

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 22 October 2017, 14:41 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models\view;

use Yii;
use yii\helpers\Url;

class UserLevel extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_level';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['level_id'];
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
			[['level_id', 'user_all'], 'integer'],
			[['users', 'user_pending', 'user_noverified', 'user_blocked'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'level_id' => Yii::t('app', 'Level'),
			'users' => Yii::t('app', 'Users'),
			'user_pending' => Yii::t('app', 'User Pending'),
			'user_noverified' => Yii::t('app', 'User Noverified'),
			'user_blocked' => Yii::t('app', 'User Blocked'),
			'user_all' => Yii::t('app', 'User All'),
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
		$this->templateColumns['level_id'] = 'level_id';
		$this->templateColumns['users'] = 'users';
		$this->templateColumns['user_pending'] = 'user_pending';
		$this->templateColumns['user_noverified'] = 'user_noverified';
		$this->templateColumns['user_blocked'] = 'user_blocked';
		$this->templateColumns['user_all'] = 'user_all';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
		}
		return true;
	}

}
