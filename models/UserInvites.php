<?php
/**
 * UserInvites
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 17 October 2017, 14:18 WIB
 * @modified date 2 May 2018, 13:30 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_invites".
 *
 * The followings are the available columns in table "ommu_user_invites":
 * @property integer $invite_id
 * @property integer $publish
 * @property integer $newsletter_id
 * @property integer $user_id
 * @property string $displayname
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

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;
use app\models\CoreSettings;

class UserInvites extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;
	use \ommu\mailer\components\traits\MailTrait;

	public $gridForbiddenColumn = ['code','invite_ip','modified_date','modified_search','updated_date'];
	public $email_i;
	public $old_invites_i;

	// Variable Search
	public $user_search;
	public $email_search;
	public $level_search;
	public $inviter_search;
	public $modified_search;

	const SCENARIO_FORM = 'createForm';
	const SCENARIO_SINGLE_EMAIL = 'singleEmail';

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
			[['email_i'], 'required', 'on' => self::SCENARIO_FORM],
			[['email_i'], 'required', 'on' => self::SCENARIO_SINGLE_EMAIL],
			[['publish', 'newsletter_id', 'user_id', 'invites', 'modified_id', 'old_invites_i'], 'integer'],
			[['email_i'], 'string'],
			[['newsletter_id', 'displayname', 'code', 'invite_date', 'invite_ip', 'modified_date', 'updated_date'], 'safe'],
			[['email_i'], 'email', 'on' => self::SCENARIO_SINGLE_EMAIL],
			[['code'], 'string', 'max' => 16],
			[['invite_ip'], 'string', 'max' => 20],
			[['displayname'], 'string', 'max' => 64],
			[['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNewsletter::className(), 'targetAttribute' => ['newsletter_id' => 'newsletter_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	// get scenarios
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_FORM] = ['email_i'];
		$scenarios[self::SCENARIO_SINGLE_EMAIL] = ['email_i'];
		return $scenarios;
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
			'displayname' => Yii::t('app', 'Displayname'),
			'code' => Yii::t('app', 'Code'),
			'invites' => Yii::t('app', 'Invites'),
			'invite_date' => Yii::t('app', 'Invite Date'),
			'invite_ip' => Yii::t('app', 'Invite Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'email_i' => Yii::t('app', 'Email'),
			'user_search' => Yii::t('app', 'User'),
			'email_search' => Yii::t('app', 'Email'),
			'level_search' => Yii::t('app', 'Level'),
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
	 * @return \ommu\users\models\query\UserInvites the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserInvites(get_called_class());
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
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['inviter_search'] = [
				'attribute' => 'inviter_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->level) ? $model->user->level->title->message : '-';
				},
			];
		}
		$this->templateColumns['displayname'] = [
			'attribute' => 'displayname',
			'value' => function($model, $key, $index, $column) {
				return $model->displayname ? $model->displayname : '-';
			},
		];
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code ? $model->code : '-';
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
		$this->templateColumns['invites'] = [
			'attribute' => 'invites',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['history/invite/index', 'invite'=>$model->primaryKey]);
				return Html::a($model->invites, $url);
			},
			'contentOptions' => ['class'=>'center'],
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

	// getInvite
	public static function getInvite($email) 
	{
		$email = strtolower($email);
		$model = self::find()->alias('t')
			->leftJoin(sprintf('%s newsletter', UserNewsletter::tableName()), 't.newsletter_id=newsletter.newsletter_id')
			->select(['t.invite_id', 't.newsletter_id'])
			->where(['t.publish' => 1])
			->andWhere(['is not', 't.user_id', null])
			->andWhere(['newsletter.email' => $email])
			->orderBy('t.invite_id DESC')
			->one();
		
		return $model;
	}

	// getInviteWithCode
	public static function getInviteWithCode($email, $code)
	{
		$email = strtolower($email);
		$model = UserInviteHistory::find()->alias('t')
			->leftJoin(sprintf('%s invite', UserInvites::tableName()), 't.invite_id=invite.invite_id')
			->leftJoin(sprintf('%s newsletter', UserNewsletter::tableName()), 'invite.newsletter_id=newsletter.newsletter_id')
			->select(['t.id', 't.invite_id'])
			->where(['t.code' => $code])
			->andWhere(['invite.publish' => 1])
			->andWhere(['newsletter.status' => 1])
			->andWhere(['newsletter.email' => $email])
			->orderBy('t.id DESC')
			->one();
		
		return $model;
	}

	/**
	 * insertInvite
	 * condition
	 ** 0 = newsletter not null
	 ** 1 = newsletter save
	 ** 2 = newsletter not save
	 */
	public static function insertInvite($email, $user_id)
	{
		$email = strtolower($email);
		$invite = self::find()->alias('t')
			->leftJoin(sprintf('%s newsletter', UserNewsletter::tableName()), 't.newsletter_id=newsletter.newsletter_id')
			->select(['t.invite_id', 't.newsletter_id', 't.user_id', 't.displayname', 't.code', 't.invites'])
			->where(['t.publish' => 1])
			->andWhere(['t.user_id' => $user_id])
			->andWhere(['newsletter.email' => $email])
			->orderBy('t.invite_id DESC')
			->one();

		$condition = 0;
		if($invite == null) {
			$invite = new UserInvites();
			$invite->scenario = self::SCENARIO_SINGLE_EMAIL;
			$invite->email_i = $email;
			$invite->user_id = $user_id;
			if($invite->save())
				$condition = 1;
			else
				$condition = 2;

		} else {
			if($invite->newsletter->user_id == null) {
				$invite->invites = $invite->invites+1;
				if($invite->save())
					$condition = 1;
				else
					$condition = 2;
			}
		}
		
		return $condition;
	}

	/**
	 * after find attributes
	 */
	public function afterFind() 
	{
		$this->old_invites_i = $this->invites;
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
						$newsletter = UserNewsletter::find()
							->select(['newsletter_id', 'user_id'])
							->where(['email' => $this->email_i])
							->one();
						if($newsletter != null && $newsletter->user_id != null)
							$this->addError('email_i', Yii::t('app', 'Email {email} sudah terdaftar sebagai member.', ['email'=>$this->email_i]));
					}
				}
				if($this->user_id == null)
					$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
				$this->code = $this->uniqueCode(8,2);

			} else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

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
			$this->email_i = strtolower($this->email_i);
			
			if($insert) {
				$newsletter = UserNewsletter::find()
					->select(['newsletter_id'])
					->where(['email' => $this->email_i])
					->one();

				if($newsletter != null)
					$this->newsletter_id = $newsletter->newsletter_id;
				else {
					$newsletter = new UserNewsletter();
					$newsletter->email_i = $this->email_i;
					if($newsletter->save())
						$this->newsletter_id = $newsletter->newsletter_id;
				}

			}
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);

		$setting = CoreSettings::find()
			->select(['signup_checkemail'])
			->where(['id' => 1])
			->one();
		
		if($this->newsletter->user_id == null) {
			$displayname = $this->displayname ? $this->displayname : $this->newsletter->email;
			$inviter = $this->user->displayname ? $this->user->displayname : $this->user->email;
			$singuplink = $setting->signup_checkemail == 1 ? Url::to(['signup/index', 'code'=>$this->code], true) : Url::to(['signup/index'], true);
			
			if($insert) {
				$template = $setting->signup_checkemail == 1 ? 'users_invite-code' : 'users_invite';
				$emailSubject = $this->parseMailSubject($template);
				$emailBody = $this->parseMailBody($template, ['displayname'=>$displayname, 'inviter'=>$inviter, 'singup-link'=>$singuplink, 'invite-code'=>$this->code]);

				Yii::$app->mailer->compose()
					->setFrom($this->getMailFrom())
					->setTo([$this->newsletter->email => $displayname])
					->setSubject($emailSubject)
					->setHtmlBody($emailBody)
					->send();

			} else {
				if($this->old_invites_i != $this->invites) {
					$template = $setting->signup_checkemail == 1 ? 'users_invite-2nd-code' : 'users_invite-2nd';
					$emailSubject = $this->parseMailSubject($template);
					$emailBody = $this->parseMailBody($template, ['displayname'=>$displayname, 'invites'=>$this->invites, 'inviter'=>$inviter, 'singup-link'=>$singuplink, 'invite-code'=>$this->code]);
	
					Yii::$app->mailer->compose()
						->setFrom($this->getMailFrom())
						->setTo([$this->newsletter->email => $displayname])
						->setSubject($emailSubject)
						->setHtmlBody($emailBody)
						->send();
				}
			}
		}
	}
}
