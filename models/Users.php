<?php
/**
 * Users
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 8 October 2017, 05:31 WIB
 * @modified date 2 May 2018, 13:29 WIB
 * @link https://ecc.ft.ugm.ac.id
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
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $displayname
 * @property string $password
 * @property string $photos
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
 *
 * The followings are the available model relations:
 * @property UserForgot[] $forgots
 * @property UserHistoryEmail[] $emails
 * @property UserHistoryLogin[] $logins
 * @property UserHistoryPassword[] $passwords
 * @property UserHistoryUsername[] $usernames
 * @property UserInvites[] $invites
 * @property UserNewsletter[] $newsletters
 * @property UserOption $option
 * @property UserVerify[] $verifies
 * @property UserLevel $level
 * @property CoreLanguages $language
 * @property Users $user
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\UploadedFile;
use ommu\users\models\Users;
use app\models\CoreLanguages;
use app\models\CoreSettings;
use ommu\users\models\view\Users as UsersView;

class Users extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\GridViewTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];
	public $old_photos_i;
	public $oldPassword;
	public $newPassword;
	public $confirmPassword;
	public $reference_id_i;
	public $invite_code_i;

	// Variable Search
	public $level_search;
	public $language_search;
	public $user_search;
	public $modified_search;

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
			[['email', 'username', 'first_name', 'last_name', 'displayname', 'password', 'salt', 'creation_ip', 'lastlogin_ip', 'lastlogin_from', 'update_ip'], 'required'],
			[['enabled', 'verified', 'level_id', 'language_id', 'deactivate', 'search', 'invisible', 'privacy', 'comments', 'modified_id'], 'integer'],
			[['displayname', 'photos', 'old_photos_i'], 'string'],
			[['photos', 'creation_date', 'modified_date', 'lastlogin_date', 'update_date', 'old_photos_i'], 'safe'],
			[['email', 'username', 'first_name', 'last_name', 'password', 'salt', 'lastlogin_from'], 'string', 'max' => 32],
			[['creation_ip', 'lastlogin_ip', 'update_ip'], 'string', 'max' => 20],
			[['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLevel::className(), 'targetAttribute' => ['level_id' => 'level_id']],
			[['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoreLanguages::className(), 'targetAttribute' => ['language_id' => 'language_id']],
		];
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
			'username' => Yii::t('app', 'Username'),
			'first_name' => Yii::t('app', 'First Name'),
			'last_name' => Yii::t('app', 'Last Name'),
			'displayname' => Yii::t('app', 'Displayname'),
			'password' => Yii::t('app', 'Password'),
			'photos' => Yii::t('app', 'Photos'),
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
			'old_photos_i' => Yii::t('app', 'Old Photos'),
			'level_search' => Yii::t('app', 'Level'),
			'language_search' => Yii::t('app', 'Language'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getForgots()
	{
		return $this->hasMany(UserForgot::className(), ['user_id' => 'user_id']);
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
	public function getUsernames()
	{
		return $this->hasMany(UserHistoryUsername::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvites()
	{
		return $this->hasMany(UserInvites::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNewsletters()
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
	public function getVerifies()
	{
		return $this->hasMany(UserVerify::className(), ['user_id' => 'user_id']);
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
	public function getLanguage()
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
	 * @inheritdoc
	 * @return \ommu\users\models\query\UsersQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UsersQuery(get_called_class());
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
		if(!Yii::$app->request->get('level')) {
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->level) ? $model->level->name : '-';
				},
			];
		}
		if(!Yii::$app->request->get('language')) {
			$this->templateColumns['language_search'] = [
				'attribute' => 'language_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->language) ? $model->language->name : '-';
				},
			];
		}
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return $model->email;
			},
		];
		$this->templateColumns['username'] = [
			'attribute' => 'username',
			'value' => function($model, $key, $index, $column) {
				return $model->username;
			},
		];
		$this->templateColumns['first_name'] = [
			'attribute' => 'first_name',
			'value' => function($model, $key, $index, $column) {
				return $model->first_name;
			},
		];
		$this->templateColumns['last_name'] = [
			'attribute' => 'last_name',
			'value' => function($model, $key, $index, $column) {
				return $model->last_name;
			},
		];
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
		$this->templateColumns['photos'] = [
			'attribute' => 'photos',
			'value' => function($model, $key, $index, $column) {
				return $model->photos;
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
			'filter' => Html::input('date', 'creation_date', Yii::$app->request->get('creation_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
			},
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
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'filter' => Html::input('date', 'lastlogin_date', Yii::$app->request->get('lastlogin_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->lastlogin_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->lastlogin_date, 'datetime') : '-';
			},
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
			'filter' => Html::input('date', 'update_date', Yii::$app->request->get('update_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->update_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->update_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['update_ip'] = [
			'attribute' => 'update_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->update_ip;
			},
		];
		$this->templateColumns['search'] = [
			'attribute' => 'search',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->search ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['invisible'] = [
			'attribute' => 'invisible',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->invisible ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['privacy'] = [
			'attribute' => 'privacy',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->privacy ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['comments'] = [
			'attribute' => 'comments',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->comments ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['enabled'] = [
			'attribute' => 'enabled',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['enabled', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->enabled, '0=null, 1=enable, 2=blocked');
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
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@webroot/public/user') : 'public/user');
	}

	/**
	 * User Salt
	 */
	public static function hashPassword($salt, $password)
	{
		return md5($salt.$password);
	}

	/**
	 * after find attributes
	 */
	public function afterFind() 
	{
		$this->old_photos_i = $this->photos;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$setting = CoreSettings::find()
			->select(['site_oauth','site_type','signup_username','signup_approve','signup_verifyemail','signup_random','signup_inviteonly','signup_checkemail'])
			->where(['id' => 1])
			->one();

		if(parent::beforeValidate()) {
			$photo_exts = $this->formatFileType($this->level->photo_exts);
			if(empty($photo_exts))
				$photo_exts = [];
			$photos = UploadedFile::getInstance($this, 'photos');

			if($photos instanceof UploadedFile && !$photos->getHasError()) {
				if(!in_array(strtolower($photos->getExtension()), $photo_exts)) {
					$this->addError('photos', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', array(
						'{name}'=>$photos->name,
						'{extensions}'=>$this->formatFileType($photo_exts, false),
					)));
				}
			} /* else {
				//if(!$this->isNewRecord)
					$this->addError('photos', Yii::t('app', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('photos'))));
			} */

			if($this->isNewRecord) {
				/**
				 * Default action
				 * = Default register member
				 * = Random password
				 * = Username required
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

				if(in_array($controller, ['admin','personal'])) {
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
						if($setting->signup_checkemail == 1 && $this->invite_code_i == '')
							$this->addError('invite_code_i', Yii::t('phrase', '{attribute} tidak boleh kosong.', ['attribute'=>$this->getAttributeLabel('invite_code_i')]));
						
						if($this->email != '') {
							if($invite != null) {
								if($invite->newsletter->user_id != null)
									$this->addError('email', Yii::t('phrase', '{attribute} anda sudah terdaftar sebagai user, silahkan login.', ['attribute'=>$this->getAttributeLabel('email')]));
									
								else {
									if($setting->signup_inviteonly == 1 && $invite->newsletter->view->invite_by == 'user')
										$this->addError('email', Yii::t('phrase', 'Maaf invite hanya bisa dilakukan oleh admin'));
									
									else {
										if($setting->signup_checkemail == 1) {
											$inviteCode = UserInvites::getInviteWithCode($this->email, $this->invite_code_i);
											if($inviteCode == null)
												$this->addError('invite_code_i', Yii::t('phrase', '{attribute} expired atau tidak terdaftar dalam sistem.', ['attribute'=>$this->getAttributeLabel('invite_code_i')]));
											else
												$this->reference_id_i = $inviteCode->user_id;
										}
									}
								}
							} else
								$this->addError('email', Yii::t('phrase', '{attribute} anda belum ada dalam daftar invite.', ['attribute'=>$this->getAttributeLabel('email')]));
							
						} else {
							if($setting->signup_checkemail == 1)
								$this->addError('invite_code_i', Yii::t('phrase', '{attribute} yang and masukan salah, silahkan lengkapi input email', ['attribute'=>$this->getAttributeLabel('invite_code_i')]));
						}
					}
				}

				// Username required
				if($setting->signup_username == 1 && $oauthCondition == 0) {
					$username = strtolower($this->username);
					if($this->username != '') {
						$user = self::find()
							->select(['user_id'])
							->where(['username' => $username])
							->one();
						if($user != null)
							$this->addError('username', Yii::t('phrase', '{attribute} already in use', ['attribute'=>$this->getAttributeLabel('username')]));
					} else
						$this->addError('username', Yii::t('phrase', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('username')]));
				}

				// Random password
				if($setting->signup_random == 1 || $oauthCondition == 1) {
					$this->confirmPassword = $this->newPassword = $this->uniqueCode(8,1);
					$this->verified = 1;
				}

			} else {
				/**
				 * Modify Mamber
				 * = Admin modify member
				 * = User modify
				 */
				
				// Admin modify member
				if(in_array($controller, ['admin','personal'])) {
					$this->modified_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
				} else {
					// User modify
					if(!in_array($controller, array('password')))
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
			$this->username = strtolower($this->username);
			if($this->newPassword != '')
				$this->password = self::hashPassword($this->salt, $this->newPassword);
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

	public function getBiodata()
	{
		return $this->hasOne(\app\modules\cv\models\CvBiodata::className(), ['user_id' => 'user_id']);
	}

	public function fields()
	{
		$fields = parent::fields();
		$fields['bio_id'] = function($model) {
			return isset($model->biodata) ? $model->biodata->bio_id : '0';
		};
		return $fields;
	}
}
