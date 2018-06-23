<?php
/**
 * ViewUsers
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_users".
 *
 * The followings are the available columns in table '_users':
 * @property string $user_id
 * @property string $token_key
 * @property string $token_password
 * @property string $token_oauth
 * @property string $emails
 * @property string $email_lastchange_date
 * @property string $email_lastchange_days
 * @property string $email_lastchange_hours
 * @property string $usernames
 * @property string $username_lastchange_date
 * @property string $username_lastchange_days
 * @property string $username_lastchange_hours
 * @property string $passwords
 * @property string $password_lastchange_date
 * @property string $password_lastchange_days
 * @property string $password_lastchange_hours
 * @property string $logins
 * @property string $lastlogin_date
 * @property string $lastlogin_days
 * @property string $lastlogin_hours
 * @property string $lastlogin_from
 */
class ViewUsers extends CActiveRecord
{
	public $defaultColumns = array();
	public $templateColumns = array();
	public $gridForbiddenColumn = array();

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
			array('user_id', 'length', 'max'=>11),
			array('token_key, token_password, token_oauth, lastlogin_from', 'length', 'max'=>32),
			array('emails, email_lastchange_days, email_lastchange_hours, usernames, username_lastchange_days, username_lastchange_hours, passwords, password_lastchange_days, password_lastchange_hours, logins, lastlogin_days, lastlogin_hours', 'length', 'max'=>21),
			array('email_lastchange_date, username_lastchange_date, password_lastchange_date, lastlogin_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, token_key, token_password, token_oauth, emails, email_lastchange_date, email_lastchange_days, email_lastchange_hours, usernames, username_lastchange_date, username_lastchange_days, username_lastchange_hours, passwords, password_lastchange_date, password_lastchange_days, password_lastchange_hours, logins, lastlogin_date, lastlogin_days, lastlogin_hours, lastlogin_from', 'safe', 'on'=>'search'),
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
		
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.token_key', strtolower($this->token_key), true);
		$criteria->compare('t.token_password', strtolower($this->token_password), true);
		$criteria->compare('t.token_oauth', strtolower($this->token_oauth), true);
		$criteria->compare('t.emails', strtolower($this->emails), true);
		if($this->email_lastchange_date != null && !in_array($this->email_lastchange_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')))
			$criteria->compare('date(t.email_lastchange_date)', date('Y-m-d', strtotime($this->email_lastchange_date)));
		$criteria->compare('t.email_lastchange_days', strtolower($this->email_lastchange_days), true);
		$criteria->compare('t.email_lastchange_hours', strtolower($this->email_lastchange_hours), true);
		$criteria->compare('t.usernames', strtolower($this->usernames), true);
		if($this->username_lastchange_date != null && !in_array($this->username_lastchange_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')))
			$criteria->compare('date(t.username_lastchange_date)', date('Y-m-d', strtotime($this->username_lastchange_date)));
		$criteria->compare('t.username_lastchange_days', strtolower($this->username_lastchange_days), true);
		$criteria->compare('t.username_lastchange_hours', strtolower($this->username_lastchange_hours), true);
		$criteria->compare('t.passwords', strtolower($this->passwords), true);
		if($this->password_lastchange_date != null && !in_array($this->password_lastchange_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')))
			$criteria->compare('date(t.password_lastchange_date)', date('Y-m-d', strtotime($this->password_lastchange_date)));
		$criteria->compare('t.password_lastchange_days', strtolower($this->password_lastchange_days), true);
		$criteria->compare('t.password_lastchange_hours', strtolower($this->password_lastchange_hours), true);
		$criteria->compare('t.logins', strtolower($this->logins), true);
		if($this->lastlogin_date != null && !in_array($this->lastlogin_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')))
			$criteria->compare('date(t.lastlogin_date)', date('Y-m-d', strtotime($this->lastlogin_date)));
		$criteria->compare('t.lastlogin_days', strtolower($this->lastlogin_days), true);
		$criteria->compare('t.lastlogin_hours', strtolower($this->lastlogin_hours), true);
		$criteria->compare('t.lastlogin_from', strtolower($this->lastlogin_from), true);

		if(!isset($_GET['ViewUsers_sort']))
			$criteria->order = 't.user_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}

	/**
	 * Get kolom untuk Grid View
	 *
	 * @param array $columns kolom dari view
	 * @return array dari grid yang aktif
	 */
	public function getGridColumn($columns=null) 
	{
		// Jika $columns kosong maka isi defaultColumns dg templateColumns
		if(empty($columns) || $columns == null) {
			array_splice($this->defaultColumns, 0);
			foreach($this->templateColumns as $key => $val) {
				if(!in_array($key, $this->gridForbiddenColumn) && !in_array($key, $this->defaultColumns))
					$this->defaultColumns[] = $val;
			}
			return $this->defaultColumns;
		}

		foreach($columns as $val) {
			if(!in_array($val, $this->gridForbiddenColumn) && !in_array($val, $this->defaultColumns)) {
				$col = $this->getTemplateColumn($val);
				if($col != null)
					$this->defaultColumns[] = $col;
			}
		}

		array_unshift($this->defaultColumns, array(
			'header' => Yii::t('app', 'No'),
			'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
			'htmlOptions' => array(
				'class' => 'center',
			),
		));

		array_unshift($this->defaultColumns, array(
			'class' => 'CCheckBoxColumn',
			'name' => 'id',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
		));

		return $this->defaultColumns;
	}

	/**
	 * Get kolom template berdasarkan id pengenal
	 *
	 * @param string $name nama pengenal
	 * @return mixed
	 */
	public function getTemplateColumn($name) 
	{
		$data = null;
		if(trim($name) == '') return $data;

		foreach($this->templateColumns as $key => $item) {
			if($name == $key) {
				$data = $item;
				break;
			}
		}
		return $data;
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
			$this->templateColumns['user_id'] = array(
				'name' => 'user_id',
				'value' => '$data->user_id',
			);
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
				'value' => '!in_array($data->email_lastchange_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->email_lastchange_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'email_lastchange_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'email_lastchange_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
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
				'value' => '!in_array($data->username_lastchange_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->username_lastchange_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'username_lastchange_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'username_lastchange_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
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
				'value' => '!in_array($data->password_lastchange_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->password_lastchange_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'password_lastchange_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'password_lastchange_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
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
				'value' => '!in_array($data->lastlogin_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->lastlogin_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'lastlogin_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'lastlogin_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
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
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}