<?php
/**
 * ViewUserNewsletter
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 3 August 2017, 14:09 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_user_newsletter".
 *
 * The followings are the available columns in table '_user_newsletter':
 * @property string $newsletter_id
 * @property integer $register
 * @property string $invite_by
 * @property string $invites
 * @property string $invite_all
 * @property string $invite_users
 * @property string $invite_user_all
 * @property string $first_invite_date
 * @property string $first_invite_user_id
 * @property string $last_invite_date
 * @property string $last_invite_user_id
 */
class ViewUserNewsletter extends CActiveRecord
{
	public $defaultColumns = array();
	public $templateColumns = array();
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewUserNewsletter the static model class
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
		return $matches[1].'._user_newsletter';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'newsletter_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('register', 'numerical', 'integerOnly'=>true),
			array('newsletter_id, first_invite_user_id, last_invite_user_id', 'length', 'max'=>11),
			array('invite_by', 'length', 'max'=>5),
			array('invites, invite_all', 'length', 'max'=>32),
			array('invite_users', 'length', 'max'=>23),
			array('invite_user_all', 'length', 'max'=>21),
			array('first_invite_date, last_invite_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('newsletter_id, register, invite_by, invites, invite_all, invite_users, invite_user_all, first_invite_date, first_invite_user_id, last_invite_date, last_invite_user_id', 'safe', 'on'=>'search'),
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
			'first_user' => array(self::BELONGS_TO, 'Users', 'first_invite_user_id'),
			'last_user' => array(self::BELONGS_TO, 'Users', 'last_invite_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'newsletter_id' => Yii::t('attribute', 'Newsletter'),
			'register' => Yii::t('attribute', 'Register'),
			'invite_by' => Yii::t('attribute', 'Invite By'),
			'invites' => Yii::t('attribute', 'Invites'),
			'invite_all' => Yii::t('attribute', 'Invite All'),
			'invite_users' => Yii::t('attribute', 'Invite Users'),
			'invite_user_all' => Yii::t('attribute', 'Invite User All'),
			'first_invite_date' => Yii::t('attribute', 'First Invite Date'),
			'first_invite_user_id' => Yii::t('attribute', 'First Invite User'),
			'last_invite_date' => Yii::t('attribute', 'Last Invite Date'),
			'last_invite_user_id' => Yii::t('attribute', 'Last Invite User'),
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

		$criteria->compare('t.newsletter_id', $this->newsletter_id);
		$criteria->compare('t.register', $this->register);
		$criteria->compare('t.invite_by', strtolower($this->invite_by), true);
		$criteria->compare('t.invites', $this->invites);
		$criteria->compare('t.invite_all', $this->invite_all);
		$criteria->compare('t.invite_users', $this->invite_users);
		$criteria->compare('t.invite_user_all', $this->invite_user_all);
		if($this->first_invite_date != null && !in_array($this->first_invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.first_invite_date)', date('Y-m-d', strtotime($this->first_invite_date)));
		$criteria->compare('t.first_invite_user_id', $this->first_invite_user_id);
		if($this->last_invite_date != null && !in_array($this->last_invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.last_invite_date)', date('Y-m-d', strtotime($this->last_invite_date)));
		$criteria->compare('t.last_invite_user_id', $this->last_invite_user_id);

		if(!Yii::app()->getRequest()->getParam('ViewUserNewsletter_sort'))
			$criteria->order = 't.newsletter_id DESC';

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
			$this->defaultColumns[] = 'newsletter_id';
			$this->defaultColumns[] = 'register';
			$this->defaultColumns[] = 'invite_by';
			$this->defaultColumns[] = 'invites';
			$this->defaultColumns[] = 'invite_all';
			$this->defaultColumns[] = 'invite_users';
			$this->defaultColumns[] = 'invite_user_all';
			$this->defaultColumns[] = 'first_invite_date';
			$this->defaultColumns[] = 'first_invite_user_id';
			$this->defaultColumns[] = 'last_invite_date';
			$this->defaultColumns[] = 'last_invite_user_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'newsletter_id',
				'value' => '$data->newsletter_id',
			);
			$this->defaultColumns[] = array(
				'name' => 'register',
				'value' => '$data->register',
			);
			$this->defaultColumns[] = array(
				'name' => 'invite_by',
				'value' => '$data->invite_by',
			);
			$this->defaultColumns[] = array(
				'name' => 'invites',
				'value' => '$data->invites',
			);
			$this->defaultColumns[] = array(
				'name' => 'invite_all',
				'value' => '$data->invite_all',
			);
			$this->defaultColumns[] = array(
				'name' => 'invite_users',
				'value' => '$data->invite_users',
			);
			$this->defaultColumns[] = array(
				'name' => 'invite_user_all',
				'value' => '$data->invite_user_all',
			);
			$this->defaultColumns[] = array(
				'name' => 'first_invite_date',
				'value' => 'Utility::dateFormat($data->first_invite_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'first_invite_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'first_invite_date_filter',
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
			$this->defaultColumns[] = array(
				'name' => 'first_invite_user_id',
				'value' => '$data->first_invite_user_id',
			);
			$this->defaultColumns[] = array(
				'name' => 'last_invite_date',
				'value' => 'Utility::dateFormat($data->last_invite_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'last_invite_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'last_invite_date_filter',
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
			$this->defaultColumns[] = array(
				'name' => 'last_invite_user_id',
				'value' => '$data->last_invite_user_id',
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