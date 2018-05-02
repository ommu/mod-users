<?php
/**
 * OmmuUserOption
 * version: 0.0.1
 *
 * This is the model class for table "ommu_user_option".
 *
 * The followings are the available columns in table "ommu_user_option":
 * @property integer $option_id
 * @property integer $ommu_status
 * @property integer $invite_limit
 * @property integer $invite_success
 * @property string $signup_from

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Agus Susilo <smartgdi@gmail.com>
 * @created date 26 March 2018, 07:28 WIB
 * @contact (+62) 857-297-29382
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\helpers\Url;

class UserOption extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_option';
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
		 [['option_id', 'signup_from'], 'required'],
			[['option_id', 'ommu_status', 'invite_limit', 'invite_success'], 'integer'],
			[['signup_from'], 'string'],
			[['option_id'], 'exist', 'skipOnError' => true, 
				'targetClass' => Users::className(), 'targetAttribute' => ['option_id' => 'user_id']],
	  ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'option_id' => Yii::t('app', 'Option'),
			'ommu_status' => Yii::t('app', 'Ommu Status'),
			'invite_limit' => Yii::t('app', 'Invite Limit'),
			'invite_success' => Yii::t('app', 'Invite Success'),
			'signup_from' => Yii::t('app', 'Signup From'),
			'user_search' => Yii::t('app', 'User'),
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
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return $model->user->username;
				},
			];
		}
		$this->templateColumns['invite_limit'] = 'invite_limit';
		$this->templateColumns['invite_success'] = 'invite_success';
		$this->templateColumns['signup_from'] = 'signup_from';
		$this->templateColumns['ommu_status'] = [
			'attribute' => 'ommu_status',
			'value' => function($model, $key, $index, $column) {
				return $model->ommu_status;
			},
			'contentOptions' => ['class'=>'center'],
		];
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
