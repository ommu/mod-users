<?php
/**
 * UserNewsletter
 * version: 0.0.1
 *
 * This is the model class for table "ommu_user_newsletter".
 *
 * The followings are the available columns in table "ommu_user_newsletter":
 * @property integer $newsletter_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $reference_id
 * @property string $email
 * @property string $subscribe_date
 * @property integer $subscribe_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $updated_ip
 *
 * The followings are the available model relations:
 * @property UserInvites[] $invites
 * @property UserNewsletterHistory[] $histories

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 17 October 2017, 14:20 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;

class UserNewsletter extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $user_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_newsletter';
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
			[['status', 'user_id', 'reference_id', 'subscribe_id', 'modified_id'], 'integer'],
			[['email', 'subscribe_id', 'modified_id', 'updated_ip'], 'required'],
			[['subscribe_date', 'modified_date', 'updated_date'], 'safe'],
			[['email'], 'string', 'max' => 32],
			[['updated_ip'], 'string', 'max' => 20],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvites()
	{
		return $this->hasMany(UserInvites::className(), ['newsletter_id' => 'newsletter_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(UserNewsletterHistory::className(), ['newsletter_id' => 'newsletter_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'status' => Yii::t('app', 'Status'),
			'user_id' => Yii::t('app', 'User'),
			'reference_id' => Yii::t('app', 'Reference'),
			'email' => Yii::t('app', 'Email'),
			'subscribe_date' => Yii::t('app', 'Subscribe Date'),
			'subscribe_id' => Yii::t('app', 'Subscribe'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated Ip'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
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
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['reference_id'] = 'reference_id';
		$this->templateColumns['email'] = 'email';
		$this->templateColumns['subscribe_date'] = [
			'attribute' => 'subscribe_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'subscribe_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->subscribe_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->subscribe_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['subscribe_id'] = 'subscribe_id';
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['updated_ip'] = 'updated_ip';
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return $model->status;
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		if(parent::beforeSave($insert)) {
			// Create action
		}
		return true;	
	}

	/**
	 * after validate attributes
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		// Create action
		
		return true;
	}
	
	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);
		// Create action
	}

	/**
	 * Before delete attributes
	 */
	public function beforeDelete() 
	{
		if(parent::beforeDelete()) {
			// Create action
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete() 
	{
		parent::afterDelete();
		// Create action
	}
}
