<?php
/**
 * UserNewsletter
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
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
 * @property string $email
 * @property integer $reference_id
 * @property integer $subscribe_id
 * @property string $creation_date
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
 * @property Users $subscribe
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\view\UserNewsletter as UserNewsletterView;

class UserNewsletter extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;
	use \ommu\mailer\components\traits\MailTrait;

	public $gridForbiddenColumn = ['creation_date','modified_date','modified_search','updated_date','updated_ip','user_search','','level_search'];
	public $email_i;

	public $user_search;
	public $reference_search;
	public $subscribe_search;
	public $modified_search;
	public $level_search;
	public $register_search;

	const SCENARIO_SINGLE_EMAIL = 'singleEmail';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_newsletter';
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
			[['email_i'], 'email', 'on' => self::SCENARIO_SINGLE_EMAIL],
			[['email_i'], 'safe'],
			[['email'], 'string', 'max' => 64],
			[['updated_ip'], 'string', 'max' => 20],
			[['reference_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['reference_id' => 'user_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
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
			'email' => Yii::t('app', 'Email'),
			'reference_id' => Yii::t('app', 'Reference'),
			'subscribe_id' => Yii::t('app', 'Subscriber'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated IP'),
			'email_i' => Yii::t('app', 'Email'),
			'user_search' => Yii::t('app', 'User'),
			'reference_search' => Yii::t('app', 'Reference'),
			'subscribe_search' => Yii::t('app', 'Subscriber'),
			'modified_search' => Yii::t('app', 'Modified'),
			'level_search' => Yii::t('app', 'Level'),
			'register_search' => Yii::t('app', 'Registered'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvites()
	{
		return $this->hasMany(UserInvites::className(), ['newsletter_id' => 'newsletter_id'])
			->alias('invites')
			->andOnCondition([sprintf('%s.publish', 'invites') => 1]);
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
	public function getHistories()
	{
		return $this->hasMany(UserNewsletterHistory::className(), ['newsletter_id' => 'newsletter_id']);
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asEmail($model->email);
			},
			'format' => 'html',
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
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->level) ? $model->user->level->title->message : '-';
				},
				'filter' => UserLevel::getLevel(),
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
		if(!Yii::$app->request->get('subscribe')) {
			$this->templateColumns['subscribe_search'] = [
				'attribute' => 'subscribe_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->subscribe) ? $model->subscribe->displayname : '-';
				},
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['updated_ip'] = [
			'attribute' => 'updated_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->updated_ip;
			},
		];
		$this->templateColumns['register_search'] = [
			'attribute' => 'register_search',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->view->register);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['status', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->status, 'Subscribe,Unsubscribe');
			},
			'filter' => $this->filterYesNo(),
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
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['newsletter_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * insertNewsletter
	 * 
	 * condition
	 * 0 = newsletter not null
	 * 1 = newsletter save
	 * 2 = newsletter not save
	 */
	public static function insertNewsletter($email, $subscribe_id=null)
	{
		$email = strtolower($email);
		if($subscribe_id === null)
			$subscribe_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

		$newsletter = self::find()
			->select(['newsletter_id'])
			->where(['email' => $email])
			->one();

		$condition = 0;
		if($newsletter == null) {
			$newsletter = new UserNewsletter();
			$newsletter->scenario = self::SCENARIO_SINGLE_EMAIL;
			$newsletter->email_i = $email;
			$newsletter->subscribe_id = $subscribe_id;
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

			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

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

				$template = 'subscribe';
				$emailSubject = $this->parseMailSubject($template, 'user');
				$emailBody = $this->parseMailBody($template, [
					'displayname'=>$displayname, 
					'unsubscribe-link'=>$unsubscribelink,
				], 'user');

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
