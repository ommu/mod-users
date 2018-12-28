<?php
/**
 * UserNewsletter
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 2 May 2018, 13:19 WIB
 * @modified date 14 November 2018, 01:10 WIB
 * @link https://github.com/ommu/mod-users
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
 * @property Users $firstInvite
 * @property Users $lastInvite
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;

class UserNewsletter extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

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
			'invite_users' => Yii::t('app', 'Invite Users'),
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
	public function getFirstInvite()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'first_invite_user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLastInvite()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'last_invite_user_id']);
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
				return $model->register;
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
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->first_invite_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'first_invite_date'),
		];
		$this->templateColumns['first_invite_user_id'] = [
			'attribute' => 'first_invite_user_id',
			'value' => function($model, $key, $index, $column) {
				return $model->first_invite_user_id;
			},
		];
		$this->templateColumns['last_invite_date'] = [
			'attribute' => 'last_invite_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->last_invite_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'last_invite_date'),
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
