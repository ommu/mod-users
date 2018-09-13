<?php
/**
 * Users
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 13 September 2018, 15:13 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_users".
 *
 * The followings are the available columns in table 'ommu_users':
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
 * @property UserForgot[] $forgotAll
 * @property UserHistoryEmail[] $emails
 * @property UserHistoryLogin[] $logins
 * @property UserHistoryPassword[] $passwords
 * @property UserHistoryUsername[] $usernames
 * @property UserInvites[] $invites
 * @property UserInvites[] $inviteAll
 * @property UserNewsletter[] $newsletters
 * @property UserNewsletter[] $newsletters1
 * @property UserOption $option
 * @property UserPhones[] $phones
 * @property UserPhones[] $phoneAll
 * @property UserVerify[] $verifies
 * @property UserVerify[] $verifyAll
 * @property ViewUsers $view
 * @property OmmuLanguages $language
 * @property UserLevel $level
 * @property Users $user
 * @property Users $modified
 */

class Users extends OActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;
	use FileTrait;

	public $gridForbiddenColumn = array('language_id','first_name','last_name','password','photos','salt','deactivate','search','invisible','privacy','comments','creation_ip','modified_date','modified_search','lastlogin_ip','lastlogin_from','update_date','update_ip');
	public $old_enabled_i;
	public $old_verified_i;
	public $old_photos_i;
	public $invite_code_i;
	public $reference_id_i;
	public $oldPassword;
	public $newPassword;
	public $confirmPassword;

	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.ommu_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('level_id, email, displayname', 'required'),
			array('enabled, verified, language_id', 'required', 'on'=>'formEdit'),
			array('
				oldPassword', 'required', 'on'=>'formChangePassword'),
			array('
				newPassword, confirmPassword', 'required', 'on'=>'formAdd, formChangePassword, resetPassword'),
			array('enabled, verified, level_id, language_id, deactivate, search, invisible, privacy, comments,
				reference_id_i', 'numerical', 'integerOnly'=>true),
			array('modified_id', 'length', 'max'=>11),
			array('
				invite_code_i', 'length', 'max'=>16),
			array('creation_ip, lastlogin_ip, update_ip', 'length', 'max'=>20),
			array('email, username, first_name, last_name, password, salt, 
				oldPassword, newPassword, confirmPassword', 'length', 'max'=>32),
			array('enabled, verified, level_id, language_id, username, first_name, last_name, displayname, password, photos, deactivate, invisible,
				old_photos_i, invite_code_i, reference_id_i, oldPassword, newPassword, confirmPassword', 'safe'),
			array('oldPassword', 'filter', 'filter' =>array($this,'validatePassword')),
			array('email', 'email'),
			array('email, username', 'unique'),
			array('username', 'match', 'pattern' => '/^[a-zA-Z0-9_.-]{0,25}$/', 'message' => Yii::t('phrase', 'Nama user hanya boleh berisi karakter, angka dan karakter (., -, _)')),
			array('
				newPassword', 'compare', 'compareAttribute' => 'confirmPassword', 'message' => 'Kedua password tidak sama.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, enabled, verified, level_id, language_id, email, username, first_name, last_name, displayname, password, photos, salt, deactivate, search, invisible, privacy, comments, creation_date, creation_ip, modified_date, modified_id, lastlogin_date, lastlogin_ip, lastlogin_from, update_date, update_ip,
				modified_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'forgots' => array(self::HAS_MANY, 'UserForgot', 'user_id', 'on'=>'forgots.publish=1'),
			'forgotAll' => array(self::HAS_MANY, 'UserForgot', 'user_id'),
			'emails' => array(self::HAS_MANY, 'UserHistoryEmail', 'user_id'),
			'logins' => array(self::HAS_MANY, 'UserHistoryLogin', 'user_id'),
			'passwords' => array(self::HAS_MANY, 'UserHistoryPassword', 'user_id'),
			'usernames' => array(self::HAS_MANY, 'UserHistoryUsername', 'user_id'),
			'invites' => array(self::HAS_MANY, 'UserInvites', 'user_id', 'on'=>'invites.publish=1'),
			'inviteAll' => array(self::HAS_MANY, 'UserInvites', 'user_id'),
			'references' => array(self::HAS_MANY, 'UserNewsletter', 'reference_id'),
			'subscribes' => array(self::HAS_MANY, 'UserNewsletter', 'user_id'),
			'option' => array(self::HAS_ONE, 'UserOption', 'option_id'),
			'phones' => array(self::HAS_MANY, 'UserPhones', 'user_id', 'on'=>'phones.publish=1'),
			'phoneAll' => array(self::HAS_MANY, 'UserPhones', 'user_id'),
			'verifies' => array(self::HAS_MANY, 'UserVerify', 'user_id', 'on'=>'verifies.publish=1'),
			'verifyAll' => array(self::HAS_MANY, 'UserVerify', 'user_id'),
			'view' => array(self::BELONGS_TO, 'ViewUsers', 'user_id'),
			'language' => array(self::BELONGS_TO, 'OmmuLanguages', 'language_id'),
			'level' => array(self::BELONGS_TO, 'UserLevel', 'level_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => Yii::t('attribute', 'User'),
			'enabled' => Yii::t('attribute', 'Enabled'),
			'verified' => Yii::t('attribute', 'Verified'),
			'level_id' => Yii::t('attribute', 'Level'),
			'language_id' => Yii::t('attribute', 'Language'),
			'email' => Yii::t('attribute', 'Email'),
			'username' => Yii::t('attribute', 'Username'),
			'first_name' => Yii::t('attribute', 'First Name'),
			'last_name' => Yii::t('attribute', 'Last Name'),
			'displayname' => Yii::t('attribute', 'Fullname'),
			'password' => Yii::t('attribute', 'Password'),
			'photos' => Yii::t('attribute', 'Photos'),
			'salt' => Yii::t('attribute', 'Salt'),
			'deactivate' => Yii::t('attribute', 'Deactivate'),
			'search' => Yii::t('attribute', 'Search'),
			'invisible' => Yii::t('attribute', 'Invisible'),
			'privacy' => Yii::t('attribute', 'Privacy'),
			'comments' => Yii::t('attribute', 'Comments'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_ip' => Yii::t('attribute', 'Creation Ip'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'lastlogin_date' => Yii::t('attribute', 'Lastlogin Date'),
			'lastlogin_ip' => Yii::t('attribute', 'Lastlogin Ip'),
			'lastlogin_from' => Yii::t('attribute', 'Last Login From'),
			'update_date' => Yii::t('attribute', 'Update Date'),
			'update_ip' => Yii::t('attribute', 'Update Ip'),
			'old_photos_i' => Yii::t('attribute', 'Old Photos'),
			'invite_code_i' => Yii::t('attribute', 'Invite Code'),
			'oldPassword' => Yii::t('attribute', 'Password'),
			'newPassword' => Yii::t('attribute', 'New Password'),
			'confirmPassword' => Yii::t('attribute', 'Confirm Password'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$controller = strtolower(Yii::app()->controller->id);

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'view' => array(
				'alias' => 'view',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		if($controller == 'o/admin')
			$criteria->addNotInCondition('t.user_id', array(1));
		else
			$criteria->compare('t.user_id', $this->user_id);
		$criteria->compare('t.enabled', Yii::app()->getRequest()->getParam('enabled') ? Yii::app()->getRequest()->getParam('enabled') : $this->enabled);
		$criteria->compare('t.verified', Yii::app()->getRequest()->getParam('verified') ? Yii::app()->getRequest()->getParam('verified') : $this->verified);
		if(Yii::app()->getRequest()->getParam('level'))
			$criteria->compare('t.level_id', Yii::app()->getRequest()->getParam('level'));
		else {
			if($controller == 'o/member') {
				$criteria->addNotInCondition('t.level_id', array(1));
				$criteria->compare('t.level_id', $this->level_id);
			} else if($controller == 'o/admin')
				$criteria->compare('t.level_id',1);
			else
				$criteria->compare('t.level_id', $this->level_id);
		}
		$criteria->compare('t.language_id', Yii::app()->getRequest()->getParam('language') ? Yii::app()->getRequest()->getParam('language') : $this->language_id);
		$criteria->compare('t.email', strtolower($this->email), true);
		$criteria->compare('t.username', strtolower($this->username), true);
		$criteria->compare('t.first_name', strtolower($this->first_name), true);
		$criteria->compare('t.last_name', strtolower($this->last_name), true);
		$criteria->compare('t.displayname', strtolower($this->displayname), true);
		$criteria->compare('t.password', strtolower($this->password), true);
		$criteria->compare('t.photos', strtolower($this->photos), true);
		$criteria->compare('t.salt', strtolower($this->salt), true);
		$criteria->compare('t.deactivate', Yii::app()->getRequest()->getParam('deactivate') ? Yii::app()->getRequest()->getParam('deactivate') : $this->deactivate);
		$criteria->compare('t.search', Yii::app()->getRequest()->getParam('search') ? Yii::app()->getRequest()->getParam('search') : $this->search);
		$criteria->compare('t.invisible', Yii::app()->getRequest()->getParam('invisible') ? Yii::app()->getRequest()->getParam('invisible') : $this->invisible);
		$criteria->compare('t.privacy', Yii::app()->getRequest()->getParam('privacy') ? Yii::app()->getRequest()->getParam('privacy') : $this->privacy);
		$criteria->compare('t.comments', Yii::app()->getRequest()->getParam('comments') ? Yii::app()->getRequest()->getParam('comments') : $this->comments);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_ip', strtolower($this->creation_ip), true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->lastlogin_date != null && !in_array($this->lastlogin_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.lastlogin_date)', date('Y-m-d', strtotime($this->lastlogin_date)));
		$criteria->compare('t.lastlogin_ip', strtolower($this->lastlogin_ip), true);
		$criteria->compare('t.lastlogin_from', strtolower($this->lastlogin_from), true);
		if($this->update_date != null && !in_array($this->update_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.update_date)', date('Y-m-d', strtotime($this->update_date)));
		$criteria->compare('t.update_ip', strtolower($this->update_ip), true);

		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('Users_sort'))
			$criteria->order = 't.user_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 50,
			),
		));
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() 
	{
		$controller = strtolower(Yii::app()->controller->id);

		if(count($this->templateColumns) == 0) {
			$this->templateColumns['_option'] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->templateColumns['_no'] = array(
				'header' => Yii::t('app', 'No'),
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			if(!in_array($controller, array('o/admin')) && !Yii::app()->getRequest()->getParam('level')) {
				$this->templateColumns['level_id'] = array(
					'name' => 'level_id',
					'value' => '$data->level->title->message ? $data->level->title->message : \'-\'',
					'filter' => UserLevel::getLevel('member'),
				);
			}
			if(!Yii::app()->getRequest()->getParam('language')) {
				$this->templateColumns['language_id'] = array(
					'name' => 'language_id',
					'value' => '$data->language->name ? $data->language->name : \'-\'',
					'filter' => OmmuLanguages::getLanguage(),
				);
			}
			$this->templateColumns['displayname'] = array(
				'name' => 'displayname',
				'value' => '$data->displayname',
			);
			$this->templateColumns['email'] = array(
				'name' => 'email',
				'value' => '$data->email',
			);
			$this->templateColumns['username'] = array(
				'name' => 'username',
				'value' => '$data->username',
			);
			$this->templateColumns['first_name'] = array(
				'name' => 'first_name',
				'value' => '$data->first_name',
			);
			$this->templateColumns['last_name'] = array(
				'name' => 'last_name',
				'value' => '$data->last_name',
			);
			$this->templateColumns['password'] = array(
				'name' => 'password',
				'value' => '$data->password',
			);
			$this->templateColumns['photos'] = array(
				'name' => 'photos',
				'value' => '$data->photos ? CHtml::link($data->photos, join(\'/\', array(Yii::app()->request->baseUrl, self::getUploadPath(false), $data->user_id, $data->photos), array(\'target\' => \'_blank\')) : \'-\'',
				'type' => 'raw',
			);
			$this->templateColumns['salt'] = array(
				'name' => 'salt',
				'value' => '$data->salt',
			);
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
			$this->templateColumns['creation_ip'] = array(
				'name' => 'creation_ip',
				'value' => '$data->creation_ip',
			);
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->modified_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'modified_date'),
			);
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
			$this->templateColumns['lastlogin_date'] = array(
				'name' => 'lastlogin_date',
				'value' => '!in_array($data->lastlogin_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->lastlogin_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
			);
			$this->templateColumns['lastlogin_ip'] = array(
				'name' => 'lastlogin_ip',
				'value' => '$data->lastlogin_ip',
			);
			$this->templateColumns['lastlogin_from'] = array(
				'name' => 'lastlogin_from',
				'value' => '$data->lastlogin_from',
			);
			$this->templateColumns['update_date'] = array(
				'name' => 'update_date',
				'value' => '!in_array($data->update_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->update_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'update_date'),
			);
			$this->templateColumns['update_ip'] = array(
				'name' => 'update_ip',
				'value' => '$data->update_ip',
			);
			$this->templateColumns['enabled'] = array(
				'name' => 'enabled',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'enabled\', array(\'id\'=>$data->user_id)), $data->enabled, Users::getEnabled())',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => self::getEnabled(),
				'type' => 'raw',
			);
			$this->templateColumns['deactivate'] = array(
				'name' => 'deactivate',
				'value' => '$data->deactivate ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['search'] = array(
				'name' => 'search',
				'value' => '$data->search ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['invisible'] = array(
				'name' => 'invisible',
				'value' => '$data->invisible ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['privacy'] = array(
				'name' => 'privacy',
				'value' => '$data->privacy ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['comments'] = array(
				'name' => 'comments',
				'value' => '$data->comments ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			if(!in_array($controller, array('o/admin'))) {
				$this->templateColumns['verified'] = array(
					'name' => 'verified',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'verified\', array(\'id\'=>$data->user_id)), $data->verified, \'Verified,Unverified\')',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Model get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * function getEnabled
	 */
	public static function getEnabled($value=null)
	{
		$items = array(
			'0'=>Yii::t('phrase', 'Disable'),
			'1'=>Yii::t('phrase', 'Enable'),
			'2'=>Yii::t('phrase', 'Blocked'),
		);

		if($value != null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? join('/', array(Yii::getPathOfAlias('webroot'), 'public/users')) : 'public/users');
	}

	/**
	 * User Salt
	 */
	public static function hashPassword($salt, $password)
	{
		return md5($salt.$password);
	}

	/**
	 * User email from token
	 */
	public static function getToken2Email($token, $type='oauth')
	{
		if($type='oauth')
			$column = 'token_oauth';
		else if($type='password')
			$column = 'token_password';
		else if($type='key')
			$column = 'token_key';

		$user = ViewUsers::model()->findByAttributes(array($column => $token));
		if($user != null)
			return $user->user->email;

		return false;
	}

	/**
	 * get Current Password
	 */
	public function validatePassword($password)
	{
		if($password) {
			$user = self::model()->findByPk($this->user_id, array(
				'select' => 'user_id, salt, password',
			));
			if($user->password !== self::hashPassword($user->salt, $password))
				$this->addError('oldPassword', 'Old password is incorrect.');
			else {
				if($this->newPassword == $this->confirmPassword && $this->newPassword == $password)
					$this->addError('newPassword', Yii::t('phrase', 'Password tidak boleh sama dengan password sebelumnya.'));
			}
		}
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();

		$this->old_enabled_i = $this->enabled;
		$this->old_verified_i = $this->verified;
		$this->old_photos_i = $this->photos;

		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);

		$setting = OmmuSettings::model()->findByPk(1, array(
			'select' => 'site_oauth, site_type, signup_username, signup_approve, signup_verifyemail, signup_random, signup_inviteonly, signup_checkemail',
		));

		if(parent::beforeValidate()) {
			$photo_exts = $this->formatFileType($this->level->photo_exts);
			if(empty($photo_exts))
				$photo_exts = array();

			$photos = CUploadedFile::getInstance($this, 'photos');
			if($photos != null) {
				$extension = pathinfo($photos->name, PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), $photo_exts))
					$this->addError('photos', Yii::t('phrase', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}.', array(
						'{name}'=>$photos->name,
						'{extensions}'=>$this->formatFileType($photo_exts, false),
					)));
			} else {
				//if($this->isNewRecord)
				if(!$this->isNewRecord && $currentAction == 'o/admin/photo')
					$this->addError('photos', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('photos'))));
			}

			if($this->isNewRecord) {
				/**
				 * Default action
				 * = Default register member
				 * = Random password
				 * = Username required
				 */
				$oauthCondition = 0;
				if($action == 'login' && $setting->site_oauth == 1)
					$oauthCondition = 1;

				$this->salt = $this->uniqueCode(16,2);

				// User Reference
				$this->email = strtolower($this->email);
				$this->reference_id_i = 0;
				if($this->email != '') {
					$settingUser = UserSetting::model()->findByPk(1, array(
						'select' => 'invite_order',
					));
					$invite = UserInvites::getInvite($this->email);
					if($invite != null && $invite->newsletter->user_id == null) {
						$reference_id_i = $settingUser && $settingUser->invite_order == 'asc' ? $invite->newsletter->view->first_invite_user_id : $invite->newsletter->view->last_invite_user_id;
						$this->reference_id_i = $reference_id_i;
					}
				}

				if(in_array($controller, array('o/admin','o/member'))) {
					// Auto Approve Users
					if($setting->signup_approve == 1)
						$this->enabled = 1;

					// Auto Verified Email User
					if($setting->signup_verifyemail == 0)
						$this->verified = 1;

					// Generate user by admin
					$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

				} else {
					$this->level_id = UserLevel::getDefault();
					$this->enabled = $setting->signup_approve == 1 ? 1 : 0;
					$this->verified = $setting->signup_verifyemail == 0 ? 1 : 0;

					// Signup by Invite (Admin or User)
					if(($setting->site_type == 1 && $setting->signup_inviteonly != 0) && $oauthCondition == 0) {
						if($setting->signup_checkemail == 1 && $this->invite_code_i == '')
							$this->addError('invite_code_i', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('invite_code_i'))));

						if($this->email != '') {
							if($invite != null) {
								if($invite->newsletter->user_id != null)
									$this->addError('email', Yii::t('phrase', 'Email anda sudah terdaftar sebagai user, silahkan login.'));

								else {
									if($setting->signup_inviteonly == 1 && $invite->newsletter->view->invite_by == 'user')
										$this->addError('email', Yii::t('phrase', 'Maaf invite hanya bisa dilakukan oleh admin'));

									else {
										if($setting->signup_checkemail == 1) {
											$inviteCode = UserInvites::getInviteWithCode($this->email, $this->invite_code_i);
											if($inviteCode == null)
												$this->addError('invite_code_i', Yii::t('phrase', 'Invite Code expired atau tidak terdaftar dalam sistem.'));
											else
												$this->reference_id_i = $inviteCode->invite->user_id;
										}
									}
								}
							} else
								$this->addError('email', Yii::t('phrase', 'Email anda belum ada dalam daftar invite.'));

						} else {
							if($setting->signup_checkemail == 1)
								$this->addError('invite_code_i', Yii::t('phrase', 'Invite Code yang and masukan salah, silahkan lengkapi input email'));
						}
					}
				}

				// Username required
				if($setting->signup_username == 1 && $oauthCondition == 0) {
					if($this->username != '') {
						$user = self::model()->findByAttributes(array('username' => strtolower($this->username)));
						if($user != null)
							$this->addError('username', Yii::t('phrase', '{attribute} already in use', array('{attribute}'=>$this->getAttributeLabel('username'))));
					} else
						$this->addError('username', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('username'))));
				}

				// Random password
				if($setting->signup_random == 1 || $oauthCondition == 1) {
					$this->confirmPassword = $this->newPassword = $this->uniqueCode(5,2);
					$this->verified = 1;
				}
				
				$this->creation_ip = $_SERVER['REMOTE_ADDR'];

			} else {
				/**
				 * Modify Mamber
				 * = Admin modify member
				 * = User modify
				 */
				
				// Admin modify member
				if(in_array($currentAction, array('o/admin/edit','o/member/edit'))) {
					$this->modified_date = date('Y-m-d H:i:s');
					$this->modified_id = Yii::app()->user->id;
				
				// User modify
				} else {
					if(!in_array($controller, array('password')))
						$this->update_date = date('Y-m-d H:i:s');
					$this->update_ip = $_SERVER['REMOTE_ADDR'];
				}
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave()) {
			$this->email = strtolower($this->email);
			$this->username = strtolower($this->username);
			if($this->newPassword != '')
				$this->password = self::hashPassword($this->salt, $this->newPassword);

			if(!$this->isNewRecord) {
				// create directory
				$this->createUploadDirectory(self::getUploadPath(), $this->user_id);
				
				$uploadPath = join('/', array(self::getUploadPath(), $this->user_id));
				$verwijderenPath = join('/', array(self::getUploadPath(), 'verwijderen'));

				$this->photos = CUploadedFile::getInstance($this, 'photos');
				if($this->photos != null) {
					if($this->photos instanceOf CUploadedFile) {
						$fileName = time().'_'.$this->user_id.'.'.strtolower($this->photos->extensionName);
						if($this->photos->saveAs(join('/', array($uploadPath, $fileName)))) {
							if($this->old_photos_i != '' && file_exists(join('/', array($uploadPath, $this->old_photos_i))))
								rename(join('/', array($uploadPath, $this->old_photos_i)), join('/', array($verwijderenPath, time().'_change_'.$this->old_photos_i)));
							$this->photos = $fileName;
						}
					}
				} else {
					if($this->photos == '')
						$this->photos = $this->old_photos_i;
				}
			}
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		Yii::import('ext.phpmailer.Mailer');

		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);

		$setting = OmmuSettings::model()->findByPk(1, array(
			'select' => 'site_type, site_title, signup_welcome, signup_adminemail',
		));

		$assets = Yii::getPathOfAlias('users.assets');
		if(!file_exists($assets))
			$assets = Yii::getPathOfAlias('ommu.users.assets');
		$_assetsUrl = Yii::app()->assetManager->publish($assets);

		parent::afterSave();

		// create directory
		$this->createUploadDirectory(self::getUploadPath(), $this->user_id);

		$uploadPath = join('/', array(self::getUploadPath(), $this->user_id));
		$verwijderenPath = join('/', array(self::getUploadPath(), 'verwijderen'));

		// Generate Verification Code
		if ($this->verified != $this->old_verified_i && $this->verified == 0) {
			$verify = new UserVerify;
			$verify->user_id = $this->user_id;
			$verify->save(false);
		}

		if($this->isNewRecord) {
			/**
			 * = New User
			 * Create user direcrtory
			 * Upload photo
			 * Update referensi newsletter
			 * Modify user options
			 * Send welcome email
			 * Send account information
			 * Send new account to email administrator
			 *
			 * = Update User
			 * Send new account information
			 * Send success email verification
			 *
			 */

			// Upload photo
			$this->photos = CUploadedFile::getInstance($this, 'photos');
			if($this->photos != null) {
				if($this->photos instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->user_id.'.'.strtolower($this->photos->extensionName);
					if($this->photos->saveAs(join('/', array($uploadPath, $fileName))))
						self::model()->updateByPk($this->user_id, array('photos'=>$fileName));
				}
			}

			// Update referensi newsletter
			if($setting->site_type == 1 && $this->reference_id_i != 0) {
				$newsletter = UserNewsletter::model()->findByAttributes(array('email'=>$this->email), array(
					'select' => 'newsletter_id, reference_id',
				));
				if($newsletter != null && $newsletter->user_id != null && $newsletter->reference_id == null) {
					$newsletter->reference_id = $this->reference_id_i;
					$newsletter->update();
				}
			}

			// Modify user options
			$ommuStatus = $this->level_id == 1 ? 1 : 0;
			UserOption::model()->updateByPk($this->user_id, array(
				'ommu_status'=>$ommuStatus,
				'signup_from'=>Yii::app()->params['product_access_system'],
			));

			// Send welcome email
			if($setting->signup_welcome == 1) {
				$welcome_search = array(
					'{displayname}', '{site_title}',
				);
				$welcome_replace = array(
					$this->displayname, $setting->site_title,
				);
				$welcome_template = 'user_welcome';
				$welcome_title = Yii::t('phrase', 'Welcome to {site_title}', array('{site_title}'=>$setting->site_title));
				$welcome_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$welcome_template.'.php';
				if(!file_exists($welcome_file))
					$welcome_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$welcome_template.'.php';
				$welcome_message = Utility::getEmailTemplate(str_ireplace($welcome_search, $welcome_replace, file_get_contents($welcome_file)));
				//Mailer::send($this->email, $this->displayname, $welcome_title, $welcome_message);
			}

			// Send account information
			$account_search = array(
				'{displayname}', '{site_title}', '{email}', '{password}',
			);
			$account_replace = array(
				$this->displayname, $setting->site_title, $this->email, $this->newPassword, 
			);
			$account_template = 'user_welcome_account';
			$account_title = Yii::t('phrase', '{site_title} Account ({displayname})', array('{site_title}'=>$setting->site_title, '{displayname}'=>$this->displayname));
			$account_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$account_template.'.php';
			if(!file_exists($account_file))
				$account_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$account_template.'.php';
			$account_message = Utility::getEmailTemplate(str_ireplace($account_search, $account_replace, file_get_contents($account_file)));
			//Mailer::send($this->email, $this->displayname, $account_title, $account_message);

			// Send new account to email administrator
			if($setting->signup_adminemail == 1)
				$signup_adminemail = 1;
				//Mailer::send(null, null, 'New Member', 'informasi member terbaru');

		} else {
			// Send new account information
			if(in_array($currentAction, array('account/reset','o/member/edit','o/admin/password')) || $controller == 'password') {
				$password_search = array(
					'{displayname}', '{site_title}', '{email}', '{password}',
				);
				$password_replace = array(
					$this->displayname, $setting->site_title, $this->email, $this->newPassword, 
				);
				$password_template = 'user_forgot_new_password';
				$password_title = Yii::t('phrase', 'Your password was changed successfully');
				$password_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$password_template.'.php';
				if(!file_exists($password_file))
					$password_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$password_template.'.php';
				$password_message = Utility::getEmailTemplate(str_ireplace($password_search, $password_replace, file_get_contents($password_file)));
				//Mailer::send($this->email, $this->displayname, $password_title, $password_message);
			}

			// Send success email verification
			if(in_array($currentAction, array('account/email'))) {
				$verify_search = array(
					'{displayname}', 
				);
				$verify_replace = array(
					$this->displayname, 
				);
				$verify_template = 'user_verify_success';
				$verify_title = Yii::t('phrase', 'Email verification succeeded');
				$verify_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$verify_template.'.php';
				if(!file_exists($verify_file))
					$verify_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$verify_template.'.php';
				$verify_message = Utility::getEmailTemplate(str_ireplace($verify_search, $verify_replace, file_get_contents($verify_file)));
				//Mailer::send($this->email, $this->displayname, $verify_title, $verify_message);
			}
		}
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() 
	{
		parent::afterDelete();

		//delete image
		$uploadPath = join('/', array(self::getUploadPath(), $this->user_id));
		$verwijderenPath = join('/', array(self::getUploadPath(), 'verwijderen'));

		if($this->photos != '' && file_exists(join('/', array($uploadPath, $this->photos))))
			rename(join('/', array($uploadPath, $this->photos)), join('/', array($verwijderenPath, time().'_deleted_'.$this->photos)));

		$this->deleteFolder($uploadPath);
	}
}