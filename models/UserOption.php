<?php
/**
 * UserOption
 * 
 * @author Agus Susilo <smartgdi@gmail.com>
 * @contact (+62)857-297-29382
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 26 March 2018, 07:28 WIB
 * @modified date 3 May 2018, 13:49 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_option".
 *
 * The followings are the available columns in table "ommu_user_option":
 * @property integer $option_id
 * @property integer $ommu_status
 * @property integer $invite_limit
 * @property integer $invite_success
 * @property string $signup_from
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserOption extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Search Variable
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
			[['signup_from'], 'required'],
			[['option_id', 'ommu_status', 'invite_limit', 'invite_success'], 'integer'],
			[['signup_from'], 'string'],
			[['option_id'], 'unique'],
			[['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['option_id' => 'user_id']],
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'option_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserOption the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserOption(get_called_class());
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
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['invite_limit'] = [
			'attribute' => 'invite_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_limit;
			},
		];
		$this->templateColumns['invite_success'] = [
			'attribute' => 'invite_success',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_success;
			},
		];
		$this->templateColumns['signup_from'] = [
			'attribute' => 'signup_from',
			'value' => function($model, $key, $index, $column) {
				return $model->signup_from;
			},
		];
		$this->templateColumns['ommu_status'] = [
			'attribute' => 'ommu_status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->ommu_status);
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['option_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
