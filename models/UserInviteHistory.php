<?php
/**
 * UserInviteHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 8 October 2017, 05:38 WIB
 * @modified date 7 May 2018, 07:06 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_user_invite_history".
 *
 * The followings are the available columns in table "ommu_user_invite_history":
 * @property integer $id
 * @property integer $invite_id
 * @property string $code
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $expired_date
 *
 * The followings are the available model relations:
 * @property UserInvites $invite
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserInviteHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $invite_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_invite_history';
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
			[['invite_id', 'code', 'invite_ip'], 'required'],
			[['invite_id'], 'integer'],
			[['invite_date', 'expired_date'], 'safe'],
			[['code'], 'string', 'max' => 16],
			[['invite_ip'], 'string', 'max' => 20],
			[['invite_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserInvites::className(), 'targetAttribute' => ['invite_id' => 'invite_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'invite_id' => Yii::t('app', 'Invite'),
			'code' => Yii::t('app', 'Code'),
			'invite_date' => Yii::t('app', 'Invite Date'),
			'invite_ip' => Yii::t('app', 'Invite Ip'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'invite_search' => Yii::t('app', 'Invite'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvite()
	{
		return $this->hasOne(UserInvites::className(), ['invite_id' => 'invite_id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\modules\user\models\query\UserInviteHistoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\user\models\query\UserInviteHistoryQuery(get_called_class());
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
		if(!Yii::$app->request->get('invite')) {
			$this->templateColumns['invite_search'] = [
				'attribute' => 'invite_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->invite) ? $model->invite->invite_id : '-';
				},
			];
		}
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['invite_date'] = [
			'attribute' => 'invite_date',
			'filter' => Html::input('date', 'invite_date', Yii::$app->request->get('invite_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->invite_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['invite_ip'] = [
			'attribute' => 'invite_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_ip;
			},
		];
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'filter' => Html::input('date', 'expired_date', Yii::$app->request->get('expired_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->expired_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->expired_date, 'datetime') : '-';
			},
			'format' => 'html',
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
			$this->invite_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			//$this->expired_date = Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d');
		}
		return true;
	}
}
