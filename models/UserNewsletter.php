<?php
/**
 * UserNewsletter
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 17 October 2017, 14:20 WIB
 * @modified date 13 November 2018, 10:01 WIB
 * @link https://github.com/ommu/mod-users
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
 * @property Users $reference
 * @property Users $user
 * @property UserNewsletterHistory[] $histories
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\view\UserNewsletter as UserNewsletterView;

class UserNewsletter extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;
	use \ommu\mailer\components\traits\MailTrait;

	public $gridForbiddenColumn = ['modified_date','modified_search','updated_date','updated_ip','level_search'];
	public $email_i;

	// Search Variable
	public $level_search;
	public $user_search;
	public $reference_search;
	public $subscribe_search;
	public $register_search;
	public $modified_search;

	const SCENARIO_SINGLE_EMAIL = 'singleEmail';

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
			[['email_i'], 'required'],
			[['status', 'user_id', 'reference_id', 'subscribe_id', 'modified_id'], 'integer'],
			[['email_i'], 'string'],
			[['email', 'subscribe_date', 'subscribe_id', 'modified_date', 'updated_date', 'updated_ip'], 'safe'],
			[['email_i'], 'email', 'on' => self::SCENARIO_SINGLE_EMAIL],
			[['email'], 'string', 'max' => 32],
			[['updated_ip'], 'string', 'max' => 20],
			[['reference_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['reference_id' => 'user_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	// get scenarios
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_SINGLE_EMAIL] = ['email_i'];
		return $scenarios;
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
			'subscribe_id' => Yii::t('app', 'Subscriber'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated Ip'),
			'email_i' => Yii::t('app', 'Email'),
			'level_search' => Yii::t('app', 'Level'),
			'user_search' => Yii::t('app', 'User'),
			'reference_search' => Yii::t('app', 'Reference'),
			'subscribe_search' => Yii::t('app', 'Subscriber'),
			'register_search' => Yii::t('app', 'Registered'),
			'modified_search' => Yii::t('app', 'Modified'),
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
	public function getReference()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'reference_id']);
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
	public function getSubscribe()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'subscribe_id']);
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
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(UserNewsletterView::className(), ['newsletter_id' => 'newsletter_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserNewsletter the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserNewsletter(get_called_class());
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
		}
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return $model->email;
			},
		];
		$this->templateColumns['subscribe_date'] = [
			'attribute' => 'subscribe_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->subscribe_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->subscribe_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'subscribe_date'),
			'format' => 'html',
		];
		if(!Yii::$app->request->get('subscribe')) {
			$this->templateColumns['subscribe_search'] = [
				'attribute' => 'subscribe_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->subscribe) ? $model->subscribe->displayname : '-';
				},
			];
		}
		if(!Yii::$app->request->get('reference')) {
			$this->templateColumns['reference_search'] = [
				'attribute' => 'reference_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->reference) ? $model->reference->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
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
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
			'format' => 'html',
		];
		$this->templateColumns['updated_ip'] = [
			'attribute' => 'updated_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->updated_ip;
			},
		];
		$this->templateColumns['register_search'] = [
			'attribute' => 'register_search',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $this->filterYesNo($model->view->register) : '-';
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['status', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->status, 'Subscribe,Unsubscribe');
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
				->where(['newsletter_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * insertNewsletter
	 * condition
	 ** 0 = newsletter not null
	 ** 1 = newsletter save
	 ** 2 = newsletter not save
	 */
	public static function insertNewsletter($email)
	{
		$email = strtolower($email);
		$newsletter = self::find()
			->select(['newsletter_id'])
			->where(['email' => $email])
			->one();
		
		$condition = 0;
		if($newsletter == null) {
			$newsletter = new UserNewsletter();
			$newsletter->scenario = self::SCENARIO_SINGLE_EMAIL;
			$newsletter->email_i = $email;
			if($newsletter->save())
				$condition = 1;
			else
				$condition = 2;
		}
		
		return $condition;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->email_i = strtolower($this->email_i);
				if($this->email_i != '') {
					$email_i = $this->formatFileType($this->email_i);
					if(count($email_i) == 1) {
						$this->email = $this->email_i;
						$newsletter = self::find()
							->select(['newsletter_id'])
							->where(['email' => $this->email])
							->one();
						if($newsletter != null)
							$this->addError('email_i', Yii::t('app', 'Email {email} sudah terdaftar pada newsletter.', ['email'=>$this->email]));
					}
				}
				if($this->subscribe_id == null)
					$this->subscribe_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

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
			$this->email = strtolower($this->email);
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
			// Guest Subscribe
			if($this->status == 1 && $this->user_id == null && $this->subscribe_id == null) {
				$displayname = $this->user->displayname ? $this->user->displayname : $this->email;
				$unsubscribelink = Url::to(['newsletter/subscribe', 'nid'=>$this->newsletter_id, 'status'=>0], true);

				$template = 'users_invite';
				$emailSubject = $this->parseMailSubject($template);
				$emailBody = $this->parseMailBody($template, ['displayname'=>$displayname, 'unsubscribe-link'=>$unsubscribelink]);

				Yii::$app->mailer->compose()
					->setFrom($this->getMailFrom())
					->setTo([$this->email => $displayname])
					->setSubject($emailSubject)
					->setHtmlBody($emailBody)
					->send();
				/*
				Yii::$app->mailer->compose()
					->setFrom('emailasale@gmail.com')
					->setTo($model->user->email)
					->setSubject(Yii::t('app', ''))
					->setTextBody(Yii::t('app', 'Plain text content'))
					->setHtmlBody('')
					->send();
				*/
			}
		} else {
			if($this->status == 1) {
				/*
				Yii::$app->mailer->compose()
					->setFrom('emailasale@gmail.com')
					->setTo($model->user->email)
					->setSubject(Yii::t('app', ''))
					->setTextBody(Yii::t('app', 'Plain text content'))
					->setHtmlBody('')
					->send();
				*/
			} else {
				/*
				Yii::$app->mailer->compose()
					->setFrom('emailasale@gmail.com')
					->setTo($model->user->email)
					->setSubject(Yii::t('app', ''))
					->setTextBody(Yii::t('app', 'Plain text content'))
					->setHtmlBody('')
					->send();
				*/
			}
		}
	}
}
