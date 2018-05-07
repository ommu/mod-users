<?php
/**
 * UserInvites
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 17 October 2017, 14:18 WIB
 * @modified date 2 May 2018, 13:30 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_user_invites".
 *
 * The followings are the available columns in table "ommu_user_invites":
 * @property integer $invite_id
 * @property integer $publish
 * @property integer $newsletter_id
 * @property integer $user_id
 * @property string $code
 * @property integer $invites
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property UserInviteHistory[] $histories
 * @property UserNewsletter $newsletter
 * @property Users $user
 * @property Users $modified
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\user\models\Users;

class UserInvites extends \app\components\ActiveRecord
{
	use \ommu\traits\GridViewTrait;

	public $gridForbiddenColumn = ['invite_ip','modified_date','modified_search','updated_date'];
	public $email_i;
	public $multiple_email_i;

	// Variable Search
	public $newsletter_search;
	public $inviter_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_invites';
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
			[['newsletter_id', 'code', 'invite_ip'], 'required'],
			[['publish', 'newsletter_id', 'user_id', 'invites', 'modified_id'], 'integer'],
			[['invite_date', 'modified_date', 'updated_date'], 'safe'],
			[['code'], 'string', 'max' => 16],
			[['invite_ip'], 'string', 'max' => 20],
			[['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNewsletter::className(), 'targetAttribute' => ['newsletter_id' => 'newsletter_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'invite_id' => Yii::t('app', 'Invite'),
			'publish' => Yii::t('app', 'Publish'),
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'user_id' => Yii::t('app', 'User'),
			'code' => Yii::t('app', 'Code'),
			'invites' => Yii::t('app', 'Invites'),
			'invite_date' => Yii::t('app', 'Invite Date'),
			'invite_ip' => Yii::t('app', 'Invite Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'email_i' => Yii::t('app', 'Email'),
			'multiple_email_i' => Yii::t('app', 'Multiple Email'),
			'newsletter_search' => Yii::t('app', 'Newsletter'),
			'inviter_search' => Yii::t('app', 'Inviter'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(UserInviteHistory::className(), ['invite_id' => 'invite_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNewsletter()
	{
		return $this->hasOne(UserNewsletter::className(), ['newsletter_id' => 'newsletter_id']);
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
	 * @inheritdoc
	 * @return \app\modules\user\models\query\UserInvitesQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \app\modules\user\models\query\UserInvitesQuery(get_called_class());
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
			$this->templateColumns['newsletter_search'] = [
				'attribute' => 'newsletter_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->newsletter) ? $model->newsletter->newsletter_id : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['inviter_search'] = [
				'attribute' => 'inviter_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['invites'] = [
			'attribute' => 'invites',
			'value' => function($model, $key, $index, $column) {
				return $model->invites;
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
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter' => Html::input('date', 'modified_date', Yii::$app->request->get('modified_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format' => 'html',
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
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['invite_id' => $id])
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
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			$this->invite_ip = $_SERVER['REMOTE_ADDR'];
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
