<?php
/**
 * UserVerify
 * version: 0.0.1
 *
 * This is the model class for table "_view_user_verify".
 *
 * The followings are the available columns in table "_view_user_verify":
 * @property integer $verify_id
 * @property integer $expired
 * @property integer $verify_day_left
 * @property integer $verify_hour_left

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 22 October 2017, 08:14 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models\view;

use Yii;
use yii\helpers\Url;

class UserVerify extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_user_verify';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['verify_id'];
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
			[['verify_id', 'expired', 'verify_day_left', 'verify_hour_left'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'verify_id' => Yii::t('app', 'Verify'),
			'expired' => Yii::t('app', 'Expired'),
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
		$this->templateColumns['verify_id'] = 'verify_id';
		$this->templateColumns['expired'] = 'expired';
		$this->templateColumns['verify_day_left'] = 'verify_day_left';
		$this->templateColumns['verify_hour_left'] = 'verify_hour_left';
	}

}
