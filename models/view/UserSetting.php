<?php
/**
 * UserSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 May 2018, 13:19 WIB
 * @link https://github.com/ommu/mod-users
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
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
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
