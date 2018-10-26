<?php
/**
 * UserNewsletterHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 05:38 WIB
 * @modified date 7 May 2018, 07:38 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_user_newsletter_history".
 *
 * The followings are the available columns in table "ommu_user_newsletter_history":
 * @property integer $id
 * @property integer $status
 * @property integer $newsletter_id
 * @property string $updated_date
 * @property string $updated_ip
 *
 * The followings are the available model relations:
 * @property UserNewsletter $newsletter
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserNewsletterHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Variable Search
	public $level_search;
	public $user_search;
	public $email_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_newsletter_history';
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
			[['status', 'newsletter_id', 'updated_ip'], 'required'],
			[['status', 'newsletter_id'], 'integer'],
			[['updated_date'], 'safe'],
			[['updated_ip'], 'string', 'max' => 20],
			[['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNewsletter::className(), 'targetAttribute' => ['newsletter_id' => 'newsletter_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'status' => Yii::t('app', 'Status'),
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated Ip'),
			'level_search' => Yii::t('app', 'Level'),
			'user_search' => Yii::t('app', 'User'),
			'email_search' => Yii::t('app', 'Email'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNewsletter()
	{
		return $this->hasOne(UserNewsletter::className(), ['newsletter_id' => 'newsletter_id']);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\users\models\query\UserNewsletterHistoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserNewsletterHistoryQuery(get_called_class());
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
		if(!Yii::$app->request->get('newsletter')) {
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->newsletter->user->level) ? $model->newsletter->user->level->title->message : '-';
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->newsletter->user) ? $model->newsletter->user->displayname : '-';
				},
			];
			$this->templateColumns['email_search'] = [
				'attribute' => 'email_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->newsletter) ? $model->newsletter->email : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['updated_ip'] = [
			'attribute' => 'updated_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->updated_ip;
			},
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->status == 1 ? Yii::t('app', 'Subscribe') : Yii::t('app', 'Unsubscribe');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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
			$this->updated_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			//$this->updated_date = Yii::$app->formatter->asDate($this->updated_date, 'php:Y-m-d');
		}
		return true;
	}
}
