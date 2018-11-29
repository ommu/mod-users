<?php
/**
 * Assignments
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 28 November 2018, 21:45 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "swt_auth_assignment".
 *
 * The followings are the available columns in table "swt_auth_assignment":
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 * The followings are the available model relations:
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class Assignments extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return self::getTableName();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['item_name', 'user_id'], 'required'],
			[['created_at'], 'integer'],
			[['item_name', 'user_id'], 'string', 'max' => 64],
			[['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'item_name' => Yii::t('app', 'Item Name'),
			'user_id' => Yii::t('app', 'User'),
			'created_at' => Yii::t('app', 'Created At'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
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
		$this->templateColumns['user_id'] = [
			'attribute' => 'user_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
			},
		];
		$this->templateColumns['item_name'] = [
			'attribute' => 'item_name',
			'value' => function($model, $key, $index, $column) {
				return $model->item_name;
			},
		];
		$this->templateColumns['created_at'] = [
			'attribute' => 'created_at',
			'value' => function($model, $key, $index, $column) {
				return $model->created_at;
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
				->where(['item_name' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * @return string assignment table name
	 */
	public static function getTableName()
	{
		return Yii::$app->authManager->assignmentTable;
	}

    /**
     * Returns all roles in the system.
     * @return Role[] all roles in the system. The array is indexed by the role names.
     */
	public static function getRoles()
	{
		$roles = Yii::$app->authManager->getRoles();

		return \yii\helpers\ArrayHelper::map($roles, 'name', 'name');
	}
}
