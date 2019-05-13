<?php
/**
 * UserForgot
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 17 October 2017, 14:17 WIB
 * @modified date 14 November 2018, 13:49 WIB
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
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\view\UserForgot as UserForgotView;

class UserForgot extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\mailer\components\traits\MailTrait;

	public $gridForbiddenColumn = ['code','forgot_date','forgot_ip','expired_date','modified_date','modified_search','deleted_date','level_search'];
	public $email_i;

	public $user_search;
	public $modified_search;
	public $level_search;
	public $expired_search;

	const SCENARIO_WITH_FORM = 'withForm';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_forgot';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['user_id'], 'required'],
			[['email_i'], 'required', 'on' => self::SCENARIO_WITH_FORM],
			[['publish', 'user_id', 'modified_id'], 'integer'],
			[['email_i'], 'email'],
			[['code', 'email_i'], 'string', 'max' => 64],
			[['forgot_ip'], 'string', 'max' => 20],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_WITH_FORM] = ['user_id','email_i'];
		return $scenarios;
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
			'email_i' => Yii::t('app', 'Email'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
			'level_search' => Yii::t('app', 'Level'),
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
			'class' => 'yii\grid\SerialColumn',
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
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->level) ? $model->user->level->title->message : '-';
				},
				'filter' => UserLevel::getLevel(),
			];
			$this->templateColumns['email_i'] = [
				'attribute' => 'email_i',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? Yii::$app->formatter->asEmail($model->user->email) : '-';
				},
				'format' => 'html',
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
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->forgot_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'forgot_date'),
		];
		$this->templateColumns['forgot_ip'] = [
			'attribute' => 'forgot_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->forgot_ip;
			},
		];
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->expired_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'expired_date'),
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
		$this->templateColumns['deleted_date'] = [
			'attribute' => 'deleted_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->deleted_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'deleted_date'),
		];
		$this->templateColumns['expired_search'] = [
			'attribute' => 'expired_search',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->view->expired);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $model->publish == 0 ? '-' : $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
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
				$validator = new yii\validators\EmailValidator();
				if($this->scenario == self::SCENARIO_WITH_FORM && $validator->validate($this->email_i) === true && $this->user_id == null) {
					$user = Users::find()
						->select(['user_id'])
						->where(['email' => $this->email_i])
						->one();
					if($user === null)
						$this->addError('email_i', Yii::t('app', '{attribute} {email-i} belum terdaftar sebagai member.', ['attribute'=>$this->getAttributeLabel('email_i'), 'email-i'=>$this->email_i]));
					else
						$this->user_id = $user->user_id;
				}
				$this->code = Yii::$app->security->generateRandomString(64);
				$this->forgot_ip = $_SERVER['REMOTE_ADDR'];

			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
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

		if($insert) {
			$template = 'forgot-password';
			$displayname = $this->user->displayname ? $this->user->displayname : $this->user->email;
			$emailSubject = $this->parseMailSubject($template);
			$emailBody = $this->parseMailBody($template, [
				'displayname' => $displayname,
				'forgot-link' => Url::to(['/user/password/reset', 'cd'=>$this->code], true),
			]);

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
