<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 2 May 2018, 13:20 WIB
 * @modified date 15 November 2018, 10:40 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_users".
 *
 * The followings are the available columns in table "_users":
 * @property integer $user_id
 * @property string $token_key
 * @property string $token_password
 * @property string $token_oauth
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Users extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_users';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['user_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['user_id'], 'required'],
			[['user_id'], 'integer'],
			[['token_key', 'token_password', 'token_oauth'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'user_id' => Yii::t('app', 'User'),
			'token_key' => Yii::t('app', 'Token Key'),
			'token_password' => Yii::t('app', 'Token Password'),
			'token_oauth' => Yii::t('app', 'Token Oauth'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['user_id'] = [
			'attribute' => 'user_id',
			'value' => function($model, $key, $index, $column) {
				return $model->user_id;
			},
		];
		$this->templateColumns['token_key'] = [
			'attribute' => 'token_key',
			'value' => function($model, $key, $index, $column) {
				return $model->token_key;
			},
		];
		$this->templateColumns['token_password'] = [
			'attribute' => 'token_password',
			'value' => function($model, $key, $index, $column) {
				return $model->token_password;
			},
		];
		$this->templateColumns['token_oauth'] = [
			'attribute' => 'token_oauth',
			'value' => function($model, $key, $index, $column) {
				return $model->token_oauth;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['user_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}
}
