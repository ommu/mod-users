<?php
/**
 * Users
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 24 February 2016, 17:58 WIB
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_user_oauth".
 *
 * The followings are the available columns in table 'ommu_user_oauth':
 * @property string $user_id
 * @property string $source_id
 * @property integer $level_id
 * @property integer $profile_id
 * @property integer $language_id
 * @property string $email
 * @property string $displayname
 * @property string $photos
 * @property integer $enabled
 * @property integer $verified
 * @property string $creation_date
 * @property string $creation_ip
 * @property string $modified_date
 * @property string $modified_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $update_date
 * @property string $update_ip
 * @property integer $locale_id
 * @property integer $timezone_id
 *
 * The followings are the available model relations:
 * @property OmmuUserLevel $level
 */
class Users extends CActiveRecord
{
	public $defaultColumns = array();

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
		return 'ommu_user_oauth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, displayname', 'required'),
			array('level_id, profile_id, language_id, enabled, verified, locale_id, timezone_id', 'required', 'on'=>'edit'),
			array('level_id, profile_id, language_id, enabled, verified, locale_id, timezone_id', 'numerical', 'integerOnly'=>true),
			array('source_id, modified_id', 'length', 'max'=>11),
			array('email', 'length', 'max'=>32),
			array('displayname', 'length', 'max'=>64),
			array('creation_ip, lastlogin_ip, update_ip', 'length', 'max'=>20),
			array('source_id, photos', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, source_id, level_id, profile_id, language_id, email, displayname, photos, enabled, verified, creation_date, creation_ip, modified_date, modified_id, lastlogin_date, lastlogin_ip, update_date, update_ip, locale_id, timezone_id', 'safe', 'on'=>'search'),
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
			'level_TO' => array(self::BELONGS_TO, 'UserLevel', 'level_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'source_id' => 'Source',
			'level_id' => 'Level',
			'profile_id' => 'Profile',
			'language_id' => 'Language',
			'email' => 'Email',
			'displayname' => 'Displayname',
			'photos' => 'Photos',
			'enabled' => 'Enabled',
			'verified' => 'Verified',
			'creation_date' => 'Creation Date',
			'creation_ip' => 'Creation Ip',
			'modified_date' => 'Modified Date',
			'modified_id' => 'Modified',
			'lastlogin_date' => 'Lastlogin Date',
			'lastlogin_ip' => 'Lastlogin Ip',
			'update_date' => 'Update Date',
			'update_ip' => 'Update Ip',
			'locale_id' => 'Locale',
			'timezone_id' => 'Timezone',
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

		$criteria->compare('t.user_id',$this->user_id,true);
		if($controller == 'o/member') {
			$criteria->addNotInCondition('t.level_id',array(1));
			$criteria->compare('t.level_id',$this->level_id);
		} else if($controller == 'o/admin') {
			$criteria->compare('t.level_id',1);
		}
		$criteria->compare('t.source_id',strtolower($this->source_id),true);
		if(isset($_GET['level']))
			$criteria->compare('t.level_id',$_GET['level']);
		else
			$criteria->compare('t.level_id',$this->level_id);
		$criteria->compare('t.profile_id',$this->profile_id);
		$criteria->compare('t.language_id',$this->language_id);
		$criteria->compare('t.email',strtolower($this->email),true);
		$criteria->compare('t.displayname',strtolower($this->displayname),true);
		$criteria->compare('t.photos',strtolower($this->photos),true);
		$criteria->compare('t.enabled',$this->enabled);
		$criteria->compare('t.verified',$this->verified);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_ip',strtolower($this->creation_ip),true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		if($this->lastlogin_date != null && !in_array($this->lastlogin_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.lastlogin_date)',date('Y-m-d', strtotime($this->lastlogin_date)));
		$criteria->compare('t.lastlogin_ip',strtolower($this->lastlogin_ip),true);
		if($this->update_date != null && !in_array($this->update_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.update_date)',date('Y-m-d', strtotime($this->update_date)));
		$criteria->compare('t.update_ip',strtolower($this->update_ip),true);
		$criteria->compare('t.locale_id',$this->locale_id);
		$criteria->compare('t.timezone_id',$this->timezone_id);

		if(!isset($_GET['Users_sort']))
			$criteria->order = 't.user_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'source_id';
			$this->defaultColumns[] = 'level_id';
			$this->defaultColumns[] = 'profile_id';
			$this->defaultColumns[] = 'language_id';
			$this->defaultColumns[] = 'email';
			$this->defaultColumns[] = 'displayname';
			$this->defaultColumns[] = 'photos';
			$this->defaultColumns[] = 'enabled';
			$this->defaultColumns[] = 'verified';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_ip';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'lastlogin_date';
			$this->defaultColumns[] = 'lastlogin_ip';
			$this->defaultColumns[] = 'update_date';
			$this->defaultColumns[] = 'update_ip';
			$this->defaultColumns[] = 'locale_id';
			$this->defaultColumns[] = 'timezone_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$controller = strtolower(Yii::app()->controller->id);
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->defaultColumns[] = array(
				'name' => 'user_id',
				'value' => '$data->user_id',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = 'displayname';
			$this->defaultColumns[] = 'email';
			if(!in_array($controller, array('o/admin'))) {
				$this->defaultColumns[] = array(
					'name' => 'level_id',
					'value' => 'Phrase::trans($data->level_TO->name,2)',
					'htmlOptions' => array(
						//'class' => 'center',
					),
					'filter'=>UserLevel::getTypeMember(),
					'type' => 'raw',
				);
			}
			//$this->defaultColumns[] = 'photos';
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = 'creation_ip';
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'enabled',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("enabled",array("id"=>$data->user_id)), $data->enabled, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Phrase::trans(588,0),
						0=>Phrase::trans(589,0),
					),
					'type' => 'raw',
				);
			}
			if(!isset($_GET['type']) && $controller != 'o/admin') {
				$this->defaultColumns[] = array(
					'name' => 'verified',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("verified",array("id"=>$data->user_id)), $data->verified, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Phrase::trans(588,0),
						0=>Phrase::trans(589,0),
					),
					'type' => 'raw',
				);
			}
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

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			$controller = strtolower(Yii::app()->controller->id);
			$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
			
			if($this->isNewRecord) {
				$setting = OmmuSettings::model()->findByPk(1, array(
					'select' => 'signup_approve, signup_verifyemail',
				));
				
				$this->level_id = UserLevel::getDefault();
				$this->profile_id = 1;
				$this->enabled = $setting->signup_approve == 1 ? 1 : 0;
				$this->verified = $setting->signup_verifyemail == 1 ? 0 : 1;
				$this->creation_ip = $_SERVER['REMOTE_ADDR'];
				
			} else {				
				// Admin modify member
				if(in_array($currentAction, array('o/admin/edit','o/member/edit'))) {
					$this->modified_date = date('Y-m-d H:i:s');
					$this->modified_id = Yii::app()->user->id;
				} else {
					$this->update_date = date('Y-m-d H:i:s');
					$this->update_ip = $_SERVER['REMOTE_ADDR'];
				}				
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();

		// Generate Verification Code
		if ($this->verified == 0) {
			$verify = new UserVerify;
			$verify->user_id = $this->user_id;
			$verify->save();
		}
		
		if($this->isNewRecord) {
			$setting = OmmuSettings::model()->findByPk(1, array(
				'select' => 'site_type, signup_welcome, signup_adminemail',
			));
				
			// Send Welcome Email
			if($setting->signup_welcome == 1) {
				SupportMailSetting::sendEmail($this->email, $this->displayname, 'Welcome', 'Selamat bergabung dengan Nirwasita Hijab and Dress Corner', 1);
			}

			// Send Account Information
			if($this->enabled == 1) {
				SupportMailSetting::sendEmail($this->email, $this->displayname, 'Account Information', 'your account information', 1);
			}

			// Send New Account to Email Administrator
			if($setting->signup_adminemail == 1) {
				SupportMailSetting::sendEmail($this->email, $this->displayname, 'New Member', 'informasi member terbaru', 0);
			}
			
		} else {
			// Send Account Information
			if($this->enabled == 1) {
				if($controller == 'verify') {
					SupportMailSetting::sendEmail($this->email, $this->displayname, 'Verify Email Success', 'Verify Email Success', 1);					
				}
			}
			
		}	
	}

}