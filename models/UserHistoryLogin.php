<?php
/**
 * UserHistoryLogin
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 05:37 WIB
 * @modified date 12 November 2018, 23:53 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_history_login".
 *
 * The followings are the available columns in table "ommu_user_history_login":
 * @property integer $id
 * @property integer $user_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserHistoryLogin extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['lastlogin_from'];

	// Search Variable
	public $user_search;
	public $level_search;
	public $email_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_history_login';
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
			[['user_id', 'lastlogin_from'], 'required'],
			[['user_id'], 'integer'],
			[['lastlogin_ip', 'lastlogin_from'], 'string', 'max' => 32],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User'),
			'lastlogin_date' => Yii::t('app', 'Lastlogin Date'),
			'lastlogin_ip' => Yii::t('app', 'Lastlogin Ip'),
			'lastlogin_from' => Yii::t('app', 'Lastlogin From'),
			'user_search' => Yii::t('app', 'User'),
			'level_search' => Yii::t('app', 'Level'),
			'email_search' => Yii::t('app', 'Email'),
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
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserHistoryLogin the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserHistoryLogin(get_called_class());
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
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->level->title->message : '-';
				},
			];
			$this->templateColumns['email_search'] = [
				'attribute' => 'email_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? Yii::$app->formatter->asEmail($model->user->email) : '-';
				},
				'format' => 'html',
			];
		}
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->lastlogin_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->lastlogin_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
			'format' => 'html',
		];
		$this->templateColumns['lastlogin_ip'] = [
			'attribute' => 'lastlogin_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_ip;
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
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			$this->lastlogin_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
