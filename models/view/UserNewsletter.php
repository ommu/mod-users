<?php
/**
 * UserNewsletter
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 2 May 2018, 13:19 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_user_newsletter".
 *
 * The followings are the available columns in table "_user_newsletter":
 * @property integer $newsletter_id
 * @property integer $register
 * @property string $invite_by
 * @property string $invites
 * @property string $invite_all
 * @property string $invite_users
 * @property integer $invite_user_all
 * @property string $first_invite_date
 * @property string $first_invite_user_id
 * @property string $last_invite_date
 * @property string $last_invite_user_id
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users as UsersModel;

class UserNewsletter extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_newsletter';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['newsletter_id'];
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
			[['newsletter_id', 'register', 'invite_user_all', 'first_invite_user_id', 'last_invite_user_id'], 'integer'],
			[['invites', 'invite_all', 'invite_users'], 'number'],
			[['first_invite_date', 'last_invite_date'], 'safe'],
			[['invite_by'], 'string', 'max' => 5],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'register' => Yii::t('app', 'Register'),
			'invite_by' => Yii::t('app', 'Invite By'),
			'invites' => Yii::t('app', 'Invites'),
			'invite_all' => Yii::t('app', 'Invite All'),
			'invite_users' => Yii::t('app', 'Invite User'),
			'invite_user_all' => Yii::t('app', 'Invite User All'),
			'first_invite_date' => Yii::t('app', 'First Invite Date'),
			'first_invite_user_id' => Yii::t('app', 'First Invite User'),
			'last_invite_date' => Yii::t('app', 'Last Invite Date'),
			'last_invite_user_id' => Yii::t('app', 'Last Invite User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFirstUser()
	{
		return $this->hasOne(UsersModel::className(), ['user_id' => 'first_invite_user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLastUser()
	{
		return $this->hasOne(UsersModel::className(), ['user_id' => 'last_invite_user_id']);
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
		$this->templateColumns['newsletter_id'] = [
			'attribute' => 'newsletter_id',
			'value' => function($model, $key, $index, $column) {
				return $model->newsletter_id;
			},
		];
		$this->templateColumns['register'] = [
			'attribute' => 'register',
			'value' => function($model, $key, $index, $column) {
				return $model->register == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
		];
		$this->templateColumns['invite_by'] = [
			'attribute' => 'invite_by',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_by;
			},
		];
		$this->templateColumns['invites'] = [
			'attribute' => 'invites',
			'value' => function($model, $key, $index, $column) {
				return $model->invites;
			},
		];
		$this->templateColumns['invite_all'] = [
			'attribute' => 'invite_all',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_all;
			},
		];
		$this->templateColumns['invite_users'] = [
			'attribute' => 'invite_users',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_users;
			},
		];
		$this->templateColumns['invite_user_all'] = [
			'attribute' => 'invite_user_all',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_user_all;
			},
		];
		$this->templateColumns['first_invite_date'] = [
			'attribute' => 'first_invite_date',
			'filter' => Html::input('date', 'first_invite_date', Yii::$app->request->get('first_invite_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->first_invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->first_invite_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['first_invite_user_id'] = [
			'attribute' => 'first_invite_user_id',
			'value' => function($model, $key, $index, $column) {
				return $model->first_invite_user_id;
			},
		];
		$this->templateColumns['last_invite_date'] = [
			'attribute' => 'last_invite_date',
			'filter' => Html::input('date', 'last_invite_date', Yii::$app->request->get('last_invite_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->last_invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->last_invite_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['last_invite_user_id'] = [
			'attribute' => 'last_invite_user_id',
			'value' => function($model, $key, $index, $column) {
				return $model->last_invite_user_id;
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
				->where(['newsletter_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
