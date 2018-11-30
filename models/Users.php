<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 05:31 WIB
 * @modified date 15 November 2018, 07:02 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_users".
 *
 * The followings are the available columns in table "ommu_users":
 * @property integer $user_id
 * @property integer $enabled
 * @property integer $verified
 * @property integer $level_id
 * @property integer $language_id
 * @property string $email
 * @property string $displayname
 * @property string $password
 * @property string $salt
 * @property integer $deactivate
 * @property integer $search
 * @property integer $invisible
 * @property integer $privacy
 * @property integer $comments
 * @property string $creation_date
 * @property string $creation_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 * @property string $update_date
 * @property string $update_ip
 * @property string $auth_key
 * @property string $jwt_claims
 *
 * The followings are the available model relations:
 * @property UserForgot[] $forgots
 * @property UserHistoryEmail[] $emails
 * @property UserHistoryLogin[] $logins
 * @property UserHistoryPassword[] $passwords
 * @property UserInvites[] $invites
 * @property UserNewsletter[] $newsletters
 * @property UserNewsletter[] $references
 * @property UserOption $option
 * @property UserPhones[] $phones
 * @property UserVerify[] $verifies
 * @property CoreLanguages $languageRltn
 * @property UserLevel $level
 * @property Users $modified
 * @property MemberUser $user
 * @property Members $member
 * @property Assignments[] $assignments
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\CoreLanguages;
use app\models\CoreSettings;
use ommu\users\models\view\Users as UsersView;
use ommu\users\models\view\UserHistory as UserHistoryView;
use ommu\member\models\view\MemberUser;
use ommu\member\models\Members;

class Users extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['language_id','password','salt','deactivate','search','invisible','privacy','comments','creation_ip','modified_date','modified_search','lastlogin_ip','lastlogin_from','update_date','update_ip','auth_key','jwt_claims'];
	public $username;
	public $photos;
	public $inviteCode;
	public $currentPassword;
	public $confirmPassword;

	public $old_enabled_i;
	public $old_verified_i;
	public $reference_id_i;
	public $password_i;
	public $assignment_i;

	// Search Variable
	public $modified_search;

	const SCENARIO_ADMIN_CREATE = 'adminCreate';
	const SCENARIO_ADMIN_UPDATE_WITH_PASSWORD = 'adminUpdateWithPassword';
	const SCENARIO_REGISTER = 'register';
	const SCENARIO_REGISTER_WITH_INVITE_CODE = 'registerWithInviteCode';
	const SCENARIO_RESET_PASSWORD = 'resetPassword';
	const SCENARIO_CHANGE_PASSWORD = 'changePassword';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_users';
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
			[['level_id', 'email', 'displayname'], 'required'],
			[['password'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
			[['password', 'confirmPassword'], 'required', 'on' => self::SCENARIO_ADMIN_UPDATE_WITH_PASSWORD],
			[['password'], 'required', 'on' => self::SCENARIO_REGISTER],
			[['password', 'inviteCode'], 'required', 'on' => self::SCENARIO_REGISTER_WITH_INVITE_CODE],
			[['password', 'confirmPassword'], 'required', 'on' => self::SCENARIO_RESET_PASSWORD],
			[['currentPassword', 'password', 'confirmPassword'], 'required', 'on' => self::SCENARIO_CHANGE_PASSWORD],
			[['enabled', 'verified', 'level_id', 'language_id', 'deactivate', 'search', 'invisible', 'privacy', 'comments', 'modified_id'], 'integer'],
			[['auth_key', 'jwt_claims'], 'string'],
			[['email'], 'email'],
			[['email'], 'unique'],
			[['password', 'confirmPassword'], 'safe'],
			['password', 'compare', 'compareAttribute' => 'confirmPassword', 'message' => Yii::t('app', 'Passwords don\'t match'), 'on' => self::SCENARIO_ADMIN_UPDATE_WITH_PASSWORD],
			['password', 'compare', 'compareAttribute' => 'confirmPassword', 'message' => Yii::t('app', 'Passwords don\'t match'), 'on' => self::SCENARIO_RESET_PASSWORD],
			['password', 'compare', 'compareAttribute' => 'confirmPassword', 'message' => Yii::t('app', 'Passwords don\'t match'), 'on' => self::SCENARIO_CHANGE_PASSWORD],
			['currentPassword', 'validatePassword', 'on' => self::SCENARIO_CHANGE_PASSWORD],
			[['email', 'displayname', 'password'], 'string', 'max' => 64],
			[['salt', 'lastlogin_from'], 'string', 'max' => 32],
			[['creation_ip', 'lastlogin_ip', 'update_ip'], 'string', 'max' => 20],
			[['inviteCode'], 'string', 'max' => 16],
			[['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoreLanguages::className(), 'targetAttribute' => ['language_id' => 'language_id']],
			[['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLevel::className(), 'targetAttribute' => ['level_id' => 'level_id']],
		];
	}

	// get scenarios
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_ADMIN_CREATE] = ['enabled', 'verified', 'level_id', 'email', 'displayname', 'password'];
		$scenarios[self::SCENARIO_ADMIN_UPDATE_WITH_PASSWORD] = ['enabled', 'verified', 'level_id', 'email', 'displayname', 'password', 'confirmPassword'];
		$scenarios[self::SCENARIO_REGISTER] = ['email', 'displayname', 'password'];
		$scenarios[self::SCENARIO_REGISTER_WITH_INVITE_CODE] = ['email', 'displayname', 'password', 'inviteCode'];
		$scenarios[self::SCENARIO_RESET_PASSWORD] = ['password', 'confirmPassword'];
		$scenarios[self::SCENARIO_CHANGE_PASSWORD] = ['currentPassword', 'password', 'confirmPassword'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'user_id' => Yii::t('app', 'User'),
			'enabled' => Yii::t('app', 'Enabled'),
			'verified' => Yii::t('app', 'Verified'),
			'level_id' => Yii::t('app', 'Level'),
			'language_id' => Yii::t('app', 'Language'),
			'email' => Yii::t('app', 'Email'),
			'displayname' => Yii::t('app', 'Displayname'),
			'password' => Yii::t('app', 'Password'),
			'salt' => Yii::t('app', 'Salt'),
			'deactivate' => Yii::t('app', 'Deactivate'),
			'search' => Yii::t('app', 'Search'),
			'invisible' => Yii::t('app', 'Invisible'),
			'privacy' => Yii::t('app', 'Privacy'),
			'comments' => Yii::t('app', 'Comments'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_ip' => Yii::t('app', 'Creation Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'lastlogin_date' => Yii::t('app', 'Lastlogin Date'),
			'lastlogin_ip' => Yii::t('app', 'Lastlogin Ip'),
			'lastlogin_from' => Yii::t('app', 'Lastlogin From'),
			'update_date' => Yii::t('app', 'Update Date'),
			'update_ip' => Yii::t('app', 'Update Ip'),
			'auth_key' => Yii::t('app', 'Auth Key'),
			'jwt_claims' => Yii::t('app', 'Jwt Claims'),
			'username' => Yii::t('app', 'Username'),
			'photos' => Yii::t('app', 'Photos'),
			'inviteCode' => Yii::t('app', 'Invite Code'),
			'currentPassword' => Yii::t('app', 'Current Password'),
			'confirmPassword' => Yii::t('app', 'Confirm Password'),
			'assignment_i' => Yii::t('app', 'Assignments'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getForgots()
	{
		return $this->hasMany(UserForgot::className(), ['user_id' => 'user_id'])
			->andOnCondition([sprintf('%s.publish', UserForgot::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEmails()
	{
		return $this->hasMany(UserHistoryEmail::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLogins()
	{
		return $this->hasMany(UserHistoryLogin::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPasswords()
	{
		return $this->hasMany(UserHistoryPassword::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvites()
	{
		return $this->hasMany(UserInvites::className(), ['inviter_id' => 'user_id'])
			->andOnCondition([sprintf('%s.publish', UserInvites::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNewsletters()
	{
		return $this->hasMany(UserNewsletter::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReferences()
	{
		return $this->hasMany(UserNewsletter::className(), ['reference_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOption()
	{
		return $this->hasOne(UserOption::className(), ['option_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPhones()
	{
		return $this->hasMany(UserPhones::className(), ['user_id' => 'user_id'])
			->andOnCondition([sprintf('%s.publish', UserPhones::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getVerifies()
	{
		return $this->hasMany(UserVerify::className(), ['user_id' => 'user_id'])
			->andOnCondition([sprintf('%s.publish', UserVerify::tableName()) => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevel()
	{
		return $this->hasOne(UserLevel::className(), ['level_id' => 'level_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLanguageRltn()
	{
		return $this->hasOne(CoreLanguages::className(), ['language_id' => 'language_id']);
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
		return $this->hasOne(UsersView::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistory()
	{
		return $this->hasOne(UserHistoryView::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(MemberUser::className(), ['user_id' => 'user_id'])
			->andOnCondition(['profile_id' => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMember()
	{
		return $this->hasOne(Members::className(), ['member_id' => 'member_id'])
			->via('user');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAssignments()
	{
		return $this->hasMany(Assignments::className(), ['user_id' => 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\Users the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\Users(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		$controller = strtolower(Yii::$app->controller->id);
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['photos'] = [
			'attribute' => 'photos',
			'value' => function($model, $key, $index, $column) {
				return Html::img(join('/', [Url::Base(), $model->photos]), ['alt' => $model->displayname]);
			},
			'format' => 'html',
		];
		if($controller == 'manage/admin' && !Yii::$app->request->get('level')) {
			$this->templateColumns['level_id'] = [
				'attribute' => 'level_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->level) ? $model->level->name_i : '-';
				},
				'filter' => UserLevel::getLevel($controller == 'manage/admin' ? 'admin' : 'member'),
			];
		}
		if(!Yii::$app->request->get('language')) {
			$this->templateColumns['language_id'] = [
				'attribute' => 'language_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->languageRltn) ? $model->languageRltn->name : '-';
				},
				'filter' => CoreLanguages::getLanguage(),
			];
		}
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asEmail($model->email);
			},
			'format' => 'html',
		];
		if(isset($this->member)) {
			$this->templateColumns['username'] = [
				'attribute' => 'username',
				'value' => function($model, $key, $index, $column) {
					return $model->username;
				},
			];
		}
		$this->templateColumns['displayname'] = [
			'attribute' => 'displayname',
			'value' => function($model, $key, $index, $column) {
				return $model->displayname;
			},
		];
		$this->templateColumns['password'] = [
			'attribute' => 'password',
			'value' => function($model, $key, $index, $column) {
				return $model->password;
			},
		];
		$this->templateColumns['salt'] = [
			'attribute' => 'salt',
			'value' => function($model, $key, $index, $column) {
				return $model->salt;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
			'format' => 'html',
		];
		$this->templateColumns['creation_ip'] = [
			'attribute' => 'creation_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->creation_ip;
			},
		];
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
		$this->templateColumns['update_date'] = [
			'attribute' => 'update_date',
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->update_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->update_date, 'datetime') : '-';
			},
			'filter' => $this->filterDatepicker($this, 'update_date'),
			'format' => 'html',
		];
		$this->templateColumns['update_ip'] = [
			'attribute' => 'update_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->update_ip;
			},
		];
		$this->templateColumns['deactivate'] = [
			'attribute' => 'deactivate',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['deactivate', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->deactivate, 'Active,Deactivate');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['search'] = [
			'attribute' => 'search',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->search);
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['invisible'] = [
			'attribute' => 'invisible',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->invisible);
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['privacy'] = [
			'attribute' => 'privacy',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->privacy);
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['comments'] = [
			'attribute' => 'comments',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->comments);
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['enabled'] = [
			'attribute' => 'enabled',
			'filter' => self::getEnabled(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['enabled', 'id'=>$model->primaryKey]);
				return $model->enabled == 2 ? Yii::t('app', 'Block') : $this->quickAction($url, $model->enabled, 'Enable,Disable');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['verified'] = [
			'attribute' => 'verified',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['verified', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->verified, 'Verified,Unverified');
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
				->where(['user_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getEnabled
	 */
	public static function getEnabled($value=null)
	{
		$items = array(
			'0' => Yii::t('app', 'Disable'),
			'1' => Yii::t('app', 'Enable'),
			'2' => Yii::t('app', 'Blocked'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * Validates password
	 *
	 * @param  string  $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password);
	}

	/* http://php.net/manual/en/function.password-hash.php
	* The used algorithm, cost and salt are returned as part of the hash.
	* Therefore, all information that's needed to verify the hash is included in it.
	* This allows the password_verify() function to verify the hash WITHOUT needing SEPARATE storage for the SALT or algorithm information.
	*/
	public function setPassword($password)
	{
		$this->password = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * {@inheritdoc}
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomKey();
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@webroot/public/users') : 'public/users');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();
		
		$this->username = isset($this->member) ? $this->member->username : '';
		$this->displayname = isset($this->member) ? $this->member->displayname : $this->displayname;
		if(isset($this->member)) {
			$uploadPath = join('/', [Members::getUploadPath(false), $this->user->member_id]);
			$photos = $this->member->photo_profile ? join('/', [$uploadPath, $this->member->photo_profile]) : '';
			$this->photos = ($photos != '' && file_exists($photos)) ? $photos : join('/', [Members::getUploadPath(false), 'default.png']);
		} else
			$this->photos = join('/', [self::getUploadPath(false), 'default.png']);
		$this->old_enabled_i = $this->enabled;
		$this->old_verified_i = $this->verified;
		$this->password_i = $this->password;
		$this->assignment_i = isset($this->assignments) ? \yii\helpers\ArrayHelper::map($this->assignments, 'item_name', 'item_name') : '';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$controller = strtolower(Yii::$app->controller->id);

		$setting = CoreSettings::find()
			->select(['site_oauth','site_type','signup_username','signup_approve','signup_verifyemail','signup_random','signup_inviteonly','signup_checkemail'])
			->where(['id' => 1])
			->one();

		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				/**
				 * Created Users
				 *
				 * Default register member
				 * Random password
				 */
				$oauthCondition = 0;
				if($this->scenario == 'login' && $setting->site_oauth == 1)
					$oauthCondition = 1;

				$this->salt = $this->uniqueCode(32,1);

				// User Reference
				$this->reference_id_i = null;
				if($this->email != '') {
					$settingUser = UserSetting::find()
						->select(['invite_order'])
						->where(['id' => 1])
						->one();
					$invite = UserInvites::getInvite($this->email);
					if($invite != null && $invite->newsletter->user_id == null) {
						$reference_id_i = $settingUser->invite_order == 'asc' ? $invite->newsletter->view->first_invite_user_id : $invite->newsletter->view->last_invite_user_id;
						$this->reference_id_i = $reference_id_i;
					}
				}

				if($this->scenario == self::SCENARIO_ADMIN_CREATE) {
					// Auto Approve Users
					if($setting->signup_approve == 1)
						$this->enabled = 1;

					// Auto Verified Email User
					if($setting->signup_verifyemail == 0)
						$this->verified = 1;

					// Generate user by admin
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

				} else {
					$this->level_id = UserLevel::getDefault();
					$this->enabled = $setting->signup_approve == 1 ? 1 : 0;
					$this->verified = $setting->signup_verifyemail == 1 ? 0 : 1;

					// Signup by Invite (Admin or User)
					if(($setting->site_type == 1 && $setting->signup_inviteonly != 0) && $oauthCondition == 0) {
						if($this->email != '') {
							if($invite != null) {
								if($invite->newsletter->user_id != null)
									$this->addError('email', Yii::t('app', '{email} sudah terdaftar sebagai user, silahkan login.', ['email'=>$this->email]));

								else {
									if($setting->signup_inviteonly == 1 && $invite->newsletter->view->invite_by == 'user')
										$this->addError('email', Yii::t('app', 'Invite hanya bisa dilakukan oleh admin'));

									else {
										if($setting->signup_checkemail == 1) {
											$inviteCode = UserInvites::getInviteWithCode($this->email, $this->inviteCode);
											if($inviteCode == null)
												$this->addError('inviteCode', Yii::t('app', '{attribute} {invite-code-i} tidak terdaftar dalam sistem.', ['attribute'=>$this->getAttributeLabel('inviteCode'), 'invite-code-i'=>$this->inviteCode]));
											else {
												if($inviteCode->view->expired)
													$this->addError('inviteCode', Yii::t('app', '{attribute} {invite-code-i} expired', ['attribute'=>$this->getAttributeLabel('inviteCode'), 'invite-code-i'=>$this->inviteCode]));
												else
													$this->reference_id_i = $inviteCode->invite->inviter_id;
											}
										}
									}
								}
							} else
								$this->addError('email', Yii::t('app', '{email} belum ada dalam daftar invite.', ['email'=>$this->email]));

						} else {
							if($setting->signup_checkemail == 1)
								$this->addError('inviteCode', Yii::t('app', '{attribute} yang and masukan salah, silahkan lengkapi input email', ['attribute'=>$this->getAttributeLabel('inviteCode')]));
						}
					}
				}

				// Random password
				if($setting->signup_random == 1 || $oauthCondition == 1) {
					$this->password = $this->uniqueCode(8,1);
					$this->verified = 1;
				}

				$this->creation_ip = $_SERVER['REMOTE_ADDR'];

			} else {
				/**
				 * Modify Users
				 * 
				 * Admin modify users
				 * User modify
				 */
				
				// Admin modify member
				if(in_array($controller, ['manage/personal','manage/admnin'])) {
					$this->modified_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

				} else {
					// User modify
					if(!in_array($this->scenario, [self::SCENARIO_RESET_PASSWORD, self::SCENARIO_CHANGE_PASSWORD]))
						$this->update_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
					$this->update_ip = $_SERVER['REMOTE_ADDR'];
				}
			}
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

			if($this->isNewRecord) {
				$this->setPassword($this->password);
				$this->generateAuthKey();
			} else {
				if($this->password)
					$this->setPassword($this->password);
				else
					$this->password = $this->password_i;

				if(!$this->auth_key)
					$this->generateAuthKey();
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
			->select(['site_type', 'site_title', 'signup_welcome', 'signup_adminemail'])
			->where(['id' => 1])
			->one();

		if($insert) {
			/**
			 * Created Users
			 * 
			 * Generate verification code
			 * Update referensi newsletter
			 * Send welcome email
			 * Send account information
			 * Send new account to email administrator
			 *
			 * Update User
			 * 
			 * Generate verification code
			 * Send new account information
			 * Send success email verification
			 *
			 */

			// Generate verification code
			if ($this->verified == 0) {
				$verify = new UserVerify;
				$verify->user_id = $this->user_id;
				$verify->save();
			}

			// Update referensi newsletter
			if($setting->site_type == 1 && $this->reference_id_i != null) {
				$newsletter = UserNewsletter::find()
					->select(['newsletter_id', 'user_id', 'reference_id'])
					->where(['email' => $this->email])
					->one();
				if($newsletter != null && $newsletter->user_id != null && $newsletter->reference_id == null) {
					$newsletter->reference_id = $this->reference_id_i;
					$newsletter->save(false, ['reference_id']);
				}
			}

			// Send account information

			// Send welcome email
			if($setting->signup_welcome == 1) {
				$signup_welcome = 1;
			}

			// Send new account to email administrator
			if($setting->signup_adminemail == 1) {
				$signup_adminemail = 1;
			}

		} else {
			// Generate verification code
			if ($this->verified != $this->old_verified_i && $this->verified == 0) {
				$verify = new UserVerify;
				$verify->user_id = $this->user_id;
				$verify->save();
			}

			// Send new account information

			// Send success email verification
			if ($this->verified != $this->old_verified_i && $this->verified == 1) {
				$verified = 1;
			}
		}
	}
}
