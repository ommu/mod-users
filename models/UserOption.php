<?php
/**
 * UserOption
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 26 March 2018, 07:28 WIB
 * @modified date 17 November 2018, 13:18 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_option".
 *
 * The followings are the available columns in table "ommu_user_option":
 * @property integer $option_id
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
use yii\helpers\Html;
use yii\helpers\Url;

class UserOption extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['option_id'], 'required'],
			[['option_id', 'invite_limit', 'invite_success'], 'integer'],
			[['signup_from'], 'string'],
			[['option_id'], 'unique'],
			[['signup_from'], 'safe'],
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
			'invite_limit' => Yii::t('app', 'Invite Limit'),
			'invite_success' => Yii::t('app', 'Invite Success'),
			'signup_from' => Yii::t('app', 'Signup From'),
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['option_id'] = [
			'attribute' => 'option_id',
			'value' => function($model, $key, $index, $column) {
				return $model->option_id;
			},
		];
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
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['option_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
