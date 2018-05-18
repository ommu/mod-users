<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 2 May 2018, 13:20 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_users".
 *
 * The followings are the available columns in table "_users":
 * @property integer $user_id
 * @property string $token_key
 * @property string $token_password
 * @property string $token_oauth
 * @property integer $emails
 * @property string $email_lastchange_date
 * @property integer $email_lastchange_days
 * @property integer $email_lastchange_hours
 * @property integer $usernames
 * @property string $username_lastchange_date
 * @property integer $username_lastchange_days
 * @property integer $username_lastchange_hours
 * @property integer $passwords
 * @property string $password_lastchange_date
 * @property integer $password_lastchange_days
 * @property integer $password_lastchange_hours
 * @property integer $logins
 * @property string $lastlogin_date
 * @property integer $lastlogin_days
 * @property integer $lastlogin_hours
 * @property string $lastlogin_from
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

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
			[['user_id', 'emails', 'email_lastchange_days', 'email_lastchange_hours', 'usernames', 'username_lastchange_days', 'username_lastchange_hours', 'passwords', 'password_lastchange_days', 'password_lastchange_hours', 'logins', 'lastlogin_days', 'lastlogin_hours'], 'integer'],
			[['email_lastchange_date', 'username_lastchange_date', 'password_lastchange_date', 'lastlogin_date'], 'safe'],
			[['token_key', 'token_password', 'token_oauth', 'lastlogin_from'], 'string', 'max' => 32],
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
			'emails' => Yii::t('app', 'Emails'),
			'email_lastchange_date' => Yii::t('app', 'Email Lastchange Date'),
			'email_lastchange_days' => Yii::t('app', 'Email Lastchange Day'),
			'email_lastchange_hours' => Yii::t('app', 'Email Lastchange Hour'),
			'usernames' => Yii::t('app', 'Usernames'),
			'username_lastchange_date' => Yii::t('app', 'Username Lastchange Date'),
			'username_lastchange_days' => Yii::t('app', 'Username Lastchange Day'),
			'username_lastchange_hours' => Yii::t('app', 'Username Lastchange Hour'),
			'passwords' => Yii::t('app', 'Passwords'),
			'password_lastchange_date' => Yii::t('app', 'Password Lastchange Date'),
			'password_lastchange_days' => Yii::t('app', 'Password Lastchange Day'),
			'password_lastchange_hours' => Yii::t('app', 'Password Lastchange Hour'),
			'logins' => Yii::t('app', 'Logins'),
			'lastlogin_date' => Yii::t('app', 'Lastlogin Date'),
			'lastlogin_days' => Yii::t('app', 'Lastlogin Day'),
			'lastlogin_hours' => Yii::t('app', 'Lastlogin Hour'),
			'lastlogin_from' => Yii::t('app', 'Lastlogin From'),
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
		$this->templateColumns['emails'] = [
			'attribute' => 'emails',
			'value' => function($model, $key, $index, $column) {
				return $model->emails;
			},
		];
		$this->templateColumns['email_lastchange_date'] = [
			'attribute' => 'email_lastchange_date',
			'filter' => Html::input('date', 'email_lastchange_date', Yii::$app->request->get('email_lastchange_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->email_lastchange_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->email_lastchange_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['email_lastchange_days'] = [
			'attribute' => 'email_lastchange_days',
			'value' => function($model, $key, $index, $column) {
				return $model->email_lastchange_days;
			},
		];
		$this->templateColumns['email_lastchange_hours'] = [
			'attribute' => 'email_lastchange_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->email_lastchange_hours;
			},
		];
		$this->templateColumns['usernames'] = [
			'attribute' => 'usernames',
			'value' => function($model, $key, $index, $column) {
				return $model->usernames;
			},
		];
		$this->templateColumns['username_lastchange_date'] = [
			'attribute' => 'username_lastchange_date',
			'filter' => Html::input('date', 'username_lastchange_date', Yii::$app->request->get('username_lastchange_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->username_lastchange_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->username_lastchange_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['username_lastchange_days'] = [
			'attribute' => 'username_lastchange_days',
			'value' => function($model, $key, $index, $column) {
				return $model->username_lastchange_days;
			},
		];
		$this->templateColumns['username_lastchange_hours'] = [
			'attribute' => 'username_lastchange_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->username_lastchange_hours;
			},
		];
		$this->templateColumns['passwords'] = [
			'attribute' => 'passwords',
			'value' => function($model, $key, $index, $column) {
				return $model->passwords;
			},
		];
		$this->templateColumns['password_lastchange_date'] = [
			'attribute' => 'password_lastchange_date',
			'filter' => Html::input('date', 'password_lastchange_date', Yii::$app->request->get('password_lastchange_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->password_lastchange_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->password_lastchange_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['password_lastchange_days'] = [
			'attribute' => 'password_lastchange_days',
			'value' => function($model, $key, $index, $column) {
				return $model->password_lastchange_days;
			},
		];
		$this->templateColumns['password_lastchange_hours'] = [
			'attribute' => 'password_lastchange_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->password_lastchange_hours;
			},
		];
		$this->templateColumns['logins'] = [
			'attribute' => 'logins',
			'value' => function($model, $key, $index, $column) {
				return $model->logins;
			},
		];
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'filter' => Html::input('date', 'lastlogin_date', Yii::$app->request->get('lastlogin_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->lastlogin_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->lastlogin_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['lastlogin_days'] = [
			'attribute' => 'lastlogin_days',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_days;
			},
		];
		$this->templateColumns['lastlogin_hours'] = [
			'attribute' => 'lastlogin_hours',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_hours;
			},
		];
		$this->templateColumns['lastlogin_from'] = [
			'attribute' => 'lastlogin_from',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_from;
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
				->where(['user_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
