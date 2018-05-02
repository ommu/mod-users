<?php
/**
 * UserForgot
 * version: 0.0.1
 *
 * This is the model class for table "_view_user_forgot".
 *
 * The followings are the available columns in table "_view_user_forgot":
 * @property integer $forgot_id
 * @property integer $expired
 * @property integer $forgot_day_left
 * @property integer $forgot_hour_left

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 22 October 2017, 08:14 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models\view;

use Yii;
use yii\helpers\Url;

class UserForgot extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_user_forgot';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['forgot_id'];
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
			[['forgot_id', 'expired', 'forgot_day_left', 'forgot_hour_left'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'forgot_id' => Yii::t('app', 'Forgot'),
			'expired' => Yii::t('app', 'Expired'),
			'forgot_day_left' => Yii::t('app', 'Forgot Day Left'),
			'forgot_hour_left' => Yii::t('app', 'Forgot Hour Left'),
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
		$this->templateColumns['forgot_id'] = 'forgot_id';
		$this->templateColumns['expired'] = 'expired';
		$this->templateColumns['forgot_day_left'] = 'forgot_day_left';
		$this->templateColumns['forgot_hour_left'] = 'forgot_hour_left';
	}

}
