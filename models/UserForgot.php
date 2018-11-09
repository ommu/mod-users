<?php
/**
 * UserForgot
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 17 October 2017, 14:17 WIB
 * @modified date 2 May 2018, 13:32 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_forgot".
 *
 * The followings are the available columns in table "ommu_user_forgot":
 * @property integer $forgot_id
 * @property integer $publish
 * @property integer $user_id
 * @property string $code
 * @property string $forgot_date
 * @property string $forgot_ip
 * @property string $expired_date
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $deleted_date
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\view\UserForgot as UserForgotView;

class UserForgot extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\mailer\components\traits\MailTrait;

	public $gridForbiddenColumn = ['code','forgot_ip','modified_date','modified_search','deleted_date'];
	public $email_i;

	// Search Variable
	public $level_search;
	public $user_search;
	public $modified_search;
	public $expired_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_forgot';
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
			[['code', 'forgot_ip', 'email_i'], 'required'],
			[['publish', 'user_id', 'modified_id'], 'integer'],
			[['user_id', 'forgot_date', 'expired_date', 'modified_date', 'deleted_date'], 'safe'],
			[['email_i'], 'email'],
			[['code'], 'string', 'max' => 64],
			[['email_i'], 'string', 'max' => 32],
			[['forgot_ip'], 'string', 'max' => 20],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'forgot_id' => Yii::t('app', 'Forgot'),
			'publish' => Yii::t('app', 'Publish'),
			'user_id' => Yii::t('app', 'User'),
			'code' => Yii::t('app', 'Code'),
			'forgot_date' => Yii::t('app', 'Forgot Date'),
			'forgot_ip' => Yii::t('app', 'Forgot Ip'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'deleted_date' => Yii::t('app', 'Deleted Date'),
			'level_search' => Yii::t('app', 'Level'),
			'user_search' => Yii::t('app', 'User'),
			'email_i' => Yii::t('app', 'Email'),
			'modified_search' => Yii::t('app', 'Modified'),
			'expired_search' => Yii::t('app', 'Expired'),
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(UserForgotView::className(), ['forgot_id' => 'forgot_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserForgot the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserForgot(get_called_class());
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
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->level) ? $model->user->level->title->message : '-';
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
			$this->templateColumns['email_i'] = [
				'attribute' => 'email_i',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->email : '-';
				},
			];
		}
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['forgot_date'] = [
			'attribute' => 'forgot_date',
			'filter' => Html::input('date', 'forgot_date', Yii::$app->request->get('forgot_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->forgot_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->forgot_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['forgot_ip'] = [
			'attribute' => 'forgot_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->forgot_ip;
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
		$this->templateColumns['deleted_date'] = [
			'attribute' => 'deleted_date',
			'filter' => Html::input('date', 'deleted_date', Yii::$app->request->get('deleted_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->deleted_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->deleted_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['expired_search'] = [
			'attribute' => 'expired_search',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->view->expired);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $model->publish == 0 ? '-' : $this->quickAction($url, $model->publish);
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
				->where(['forgot_id' => $id])
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
			if($this->isNewRecord) {
				if(preg_match('/@/', $this->email_i)) {
					$user = Users::find()
						->select(['user_id','email'])
						->where(['email' => $this->email_i])
						->one();
				} else {
					$user = Users::find()
						->select(['user_id','username'])
						->where(['username' => $this->email_i])
						->one();
				}
				if($user === null)
					$this->addError('email_i', Yii::t('app', '{attribute} {email-i} belum terdaftar sebagai member.', ['attribute'=>$this->getAttributeLabel('email_i'), 'email-i'=>$this->email_i]));
				else
					$this->user_id = $user->user_id;

				$this->code = $this->uniqueCode();
				$this->forgot_ip = $_SERVER['REMOTE_ADDR'];
			} else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			//$this->expired_date = Yii::$app->formatter->asDate($this->expired_date, 'php:Y-m-d H:i:s');
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);

		if($insert) {
			$template = 'users_forgot-password';
			$displayname = $this->user->displayname ? $this->user->displayname : $this->user->email;
			$forgotlink = Url::to(['password/reset', 'cd'=>$this->code, 'lstlgntkn'=>$this->user->view->token_oauth], true);

			$emailSubject = $this->parseMailSubject($template);
			$emailBody = $this->parseMailBody($template, ['displayname'=>$displayname, 'forgot-link'=>$forgotlink]);

			Yii::$app->mailer->compose()
				->setFrom($this->getMailFrom())
				->setTo([$this->user->email => $displayname])
				->setSubject($emailSubject)
				->setHtmlBody($emailBody)
				->send();

			//Update all forgot password history
			self::updateAll(['publish' => 0], 'forgot_id <> :forgot_id and publish = :publish and user_id = :user_id',  [':forgot_id'=>$this->forgot_id, ':publish'=>1, ':user_id'=>$this->user_id]);
		}
	}
}
