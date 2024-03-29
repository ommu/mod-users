<?php
/**
 * ViewUsers
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 25 July 2018, 05:53 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_users".
 *
 * The followings are the available columns in table '_users':
 * @property integer $user_id
 * @property string $token_key
 * @property string $token_password
 * @property string $token_oauth
 * @property integer $emails
 * @property string $email_lastchange_date
 * @property integer $email_lastchange_days
 * @property integer $email_lastchange_hours
 * @property integer $usernames
 * @property string $username_lastchange_date
 * @property integer $username_lastchange_days
 * @property integer $username_lastchange_hours
 * @property integer $passwords
 * @property string $password_lastchange_date
 * @property integer $password_lastchange_days
 * @property integer $password_lastchange_hours
 * @property integer $logins
 * @property string $lastlogin_date
 * @property integer $lastlogin_days
 * @property integer $lastlogin_hours
 * @property string $lastlogin_from
 *
 * The followings are the available model relations:
 * @property Users $user
 */

class ViewUsers extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewUsers the static model class
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
		return $matches[1].'._users';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'user_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, emails, email_lastchange_days, email_lastchange_hours, usernames, username_lastchange_days, username_lastchange_hours, passwords, password_lastchange_days, password_lastchange_hours, logins, lastlogin_days, lastlogin_hours', 'numerical', 'integerOnly'=>true),
			array('emails, email_lastchange_days, email_lastchange_hours, usernames, username_lastchange_days, username_lastchange_hours, passwords, password_lastchange_days, password_lastchange_hours, logins, lastlogin_days, lastlogin_hours', 'safe'),
			array('user_id', 'length', 'max'=>11),
			array('emails, email_lastchange_days, email_lastchange_hours, usernames, username_lastchange_days, username_lastchange_hours, passwords, password_lastchange_days, password_lastchange_hours, logins, lastlogin_days, lastlogin_hours', 'length', 'max'=>21),
			array('token_key, token_password, token_oauth, lastlogin_from', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, token_key, token_password, token_oauth, emails, email_lastchange_date, email_lastchange_days, email_lastchange_hours, usernames, username_lastchange_date, username_lastchange_days, username_lastchange_hours, passwords, password_lastchange_date, password_lastchange_days, password_lastchange_hours, logins, lastlogin_date, lastlogin_days, lastlogin_hours, lastlogin_from,
				user_search', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => Yii::t('attribute', 'User'),
			'token_key' => Yii::t('attribute', 'Token Key'),
			'token_password' => Yii::t('attribute', 'Token Password'),
			'token_oauth' => Yii::t('attribute', 'Token Oauth'),
			'emails' => Yii::t('attribute', 'Emails'),
			'email_lastchange_date' => Yii::t('attribute', 'Email Lastchange Date'),
			'email_lastchange_days' => Yii::t('attribute', 'Email Lastchange Days'),
			'email_lastchange_hours' => Yii::t('attribute', 'Email Lastchange Hours'),
			'usernames' => Yii::t('attribute', 'Usernames'),
			'username_lastchange_date' => Yii::t('attribute', 'Username Lastchange Date'),
			'username_lastchange_days' => Yii::t('attribute', 'Username Lastchange Days'),
			'username_lastchange_hours' => Yii::t('attribute', 'Username Lastchange Hours'),
			'passwords' => Yii::t('attribute', 'Passwords'),
			'password_lastchange_date' => Yii::t('attribute', 'Password Lastchange Date'),
			'password_lastchange_days' => Yii::t('attribute', 'Password Lastchange Days'),
			'password_lastchange_hours' => Yii::t('attribute', 'Password Lastchange Hours'),
			'logins' => Yii::t('attribute', 'Logins'),
			'lastlogin_date' => Yii::t('attribute', 'Lastlogin Date'),
			'lastlogin_days' => Yii::t('attribute', 'Lastlogin Days'),
			'lastlogin_hours' => Yii::t('attribute', 'Lastlogin Hours'),
			'lastlogin_from' => Yii::t('attribute', 'Lastlogin From'),
			'user_search' => Yii::t('attribute', 'User'),
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

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'user' => array(
				'alias' => 'user',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.token_key', strtolower($this->token_key), true);
		$criteria->compare('t.token_password', strtolower($this->token_password), true);
		$criteria->compare('t.token_oauth', strtolower($this->token_oauth), true);
		$criteria->compare('t.emails', strtolower($this->emails), true);
		if($this->email_lastchange_date != null && !in_array($this->email_lastchange_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.email_lastchange_date)', date('Y-m-d', strtotime($this->email_lastchange_date)));
		$criteria->compare('t.email_lastchange_days', $this->email_lastchange_days);
		$criteria->compare('t.email_lastchange_hours', $this->email_lastchange_hours);
		$criteria->compare('t.usernames', $this->usernames);
		if($this->username_lastchange_date != null && !in_array($this->username_lastchange_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.username_lastchange_date)', date('Y-m-d', strtotime($this->username_lastchange_date)));
		$criteria->compare('t.username_lastchange_days', $this->username_lastchange_days);
		$criteria->compare('t.username_lastchange_hours', $this->username_lastchange_hours);
		$criteria->compare('t.passwords', $this->passwords);
		if($this->password_lastchange_date != null && !in_array($this->password_lastchange_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.password_lastchange_date)', date('Y-m-d', strtotime($this->password_lastchange_date)));
		$criteria->compare('t.password_lastchange_days', $this->password_lastchange_days);
		$criteria->compare('t.password_lastchange_hours', $this->password_lastchange_hours);
		$criteria->compare('t.logins', $this->logins);
		if($this->lastlogin_date != null && !in_array($this->lastlogin_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.lastlogin_date)', date('Y-m-d', strtotime($this->lastlogin_date)));
		$criteria->compare('t.lastlogin_days', $this->lastlogin_days);
		$criteria->compare('t.lastlogin_hours', $this->lastlogin_hours);
		$criteria->compare('t.lastlogin_from', strtolower($this->lastlogin_from), true);

		$criteria->compare('user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('ViewUsers_sort'))
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
	protected function afterConstruct() {
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
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['token_key'] = array(
				'name' => 'token_key',
				'value' => '$data->token_key',
			);
			$this->templateColumns['token_password'] = array(
				'name' => 'token_password',
				'value' => '$data->token_password',
			);
			$this->templateColumns['token_oauth'] = array(
				'name' => 'token_oauth',
				'value' => '$data->token_oauth',
			);
			$this->templateColumns['emails'] = array(
				'name' => 'emails',
				'value' => '$data->emails',
			);
			$this->templateColumns['email_lastchange_date'] = array(
				'name' => 'email_lastchange_date',
				'value' => '!in_array($data->email_lastchange_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->email_lastchange_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'email_lastchange_date'),
			);
			$this->templateColumns['email_lastchange_days'] = array(
				'name' => 'email_lastchange_days',
				'value' => '$data->email_lastchange_days',
			);
			$this->templateColumns['email_lastchange_hours'] = array(
				'name' => 'email_lastchange_hours',
				'value' => '$data->email_lastchange_hours',
			);
			$this->templateColumns['usernames'] = array(
				'name' => 'usernames',
				'value' => '$data->usernames',
			);
			$this->templateColumns['username_lastchange_date'] = array(
				'name' => 'username_lastchange_date',
				'value' => '!in_array($data->username_lastchange_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->username_lastchange_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'username_lastchange_date'),
			);
			$this->templateColumns['username_lastchange_days'] = array(
				'name' => 'username_lastchange_days',
				'value' => '$data->username_lastchange_days',
			);
			$this->templateColumns['username_lastchange_hours'] = array(
				'name' => 'username_lastchange_hours',
				'value' => '$data->username_lastchange_hours',
			);
			$this->templateColumns['passwords'] = array(
				'name' => 'passwords',
				'value' => '$data->passwords',
			);
			$this->templateColumns['password_lastchange_date'] = array(
				'name' => 'password_lastchange_date',
				'value' => '!in_array($data->password_lastchange_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->password_lastchange_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'password_lastchange_date'),
			);
			$this->templateColumns['password_lastchange_days'] = array(
				'name' => 'password_lastchange_days',
				'value' => '$data->password_lastchange_days',
			);
			$this->templateColumns['password_lastchange_hours'] = array(
				'name' => 'password_lastchange_hours',
				'value' => '$data->password_lastchange_hours',
			);
			$this->templateColumns['logins'] = array(
				'name' => 'logins',
				'value' => '$data->logins',
			);
			$this->templateColumns['lastlogin_date'] = array(
				'name' => 'lastlogin_date',
				'value' => '!in_array($data->lastlogin_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->lastlogin_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
			);
			$this->templateColumns['lastlogin_days'] = array(
				'name' => 'lastlogin_days',
				'value' => '$data->lastlogin_days',
			);
			$this->templateColumns['lastlogin_hours'] = array(
				'name' => 'lastlogin_hours',
				'value' => '$data->lastlogin_hours',
			);
			$this->templateColumns['lastlogin_from'] = array(
				'name' => 'lastlogin_from',
				'value' => '$data->lastlogin_from',
			);
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
}