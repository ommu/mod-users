<?php
/**
 * UserSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 May 2018, 13:19 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_user_setting".
 *
 * The followings are the available columns in table "_user_setting":
 * @property integer $id
 * @property integer $forgot_difference_hours
 * @property integer $verify_difference_hours
 * @property integer $invite_difference_hours
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserSetting extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_setting';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
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
			[['id', 'forgot_difference_hours', 'verify_difference_hours', 'invite_difference_hours'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'forgot_difference_hours' => Yii::t('app', 'Forgot Difference Hour'),
			'verify_difference_hours' => Yii::t('app', 'Verify Difference Hour'),
			'invite_difference_hours' => Yii::t('app', 'Invite Difference Hour'),
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
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return $model->id;
			},
		];
		$this->templateColumns['forgot_difference_hours'] = [
			'attribute' => 'forgot_difference_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->forgot_difference_hours;
			},
		];
		$this->templateColumns['verify_difference_hours'] = [
			'attribute' => 'verify_difference_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_difference_hours;
			},
		];
		$this->templateColumns['invite_difference_hours'] = [
			'attribute' => 'invite_difference_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_difference_hours;
			},
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
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}