<?php
/**
 * ViewUserNewsletter
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 30 November 2017, 07:37 WIB
 * @link http://opensource.ommu.co
 *
 * This is the model class for table "_view_user_newsletter".
 *
 * The followings are the available columns in table '_view_user_newsletter':
 * @property string $newsletter_id
 * @property string $user_id
 * @property integer $register
 * @property string $register_date
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
		return $matches[1].'._view_user_newsletter';
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
			array('newsletter_id, user_id, first_invite_user_id, last_invite_user_id', 'length', 'max'=>11),
			array('register_date', 'length', 'max'=>19),
			array('invite_by', 'length', 'max'=>5),
			array('invites, invite_all', 'length', 'max'=>32),
			array('invite_users', 'length', 'max'=>23),
			array('invite_user_all', 'length', 'max'=>21),
			array('first_invite_date, last_invite_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('newsletter_id, user_id, register, register_date, invite_by, invites, invite_all, invite_users, invite_user_all, first_invite_date, first_invite_user_id, last_invite_date, last_invite_user_id', 'safe', 'on'=>'search'),
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
			'newsletter_id' => Yii::t('attribute', 'Newsletter'),
			'user_id' => Yii::t('attribute', 'User'),
			'register' => Yii::t('attribute', 'Register'),
			'register_date' => Yii::t('attribute', 'Register Date'),
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
		
		$criteria->compare('t.newsletter_id', strtolower($this->newsletter_id), true);
		$criteria->compare('t.user_id', isset($_GET['user']) ? $_GET['user'] : $this->user_id);
		$criteria->compare('t.register', $this->register);
		$criteria->compare('t.register_date', strtolower($this->register_date), true);
		$criteria->compare('t.invite_by', strtolower($this->invite_by), true);
		$criteria->compare('t.invites', strtolower($this->invites), true);
		$criteria->compare('t.invite_all', strtolower($this->invite_all), true);
		$criteria->compare('t.invite_users', strtolower($this->invite_users), true);
		$criteria->compare('t.invite_user_all', strtolower($this->invite_user_all), true);
		if($this->first_invite_date != null && !in_array($this->first_invite_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.first_invite_date)', date('Y-m-d', strtotime($this->first_invite_date)));
		$criteria->compare('t.first_invite_user_id', strtolower($this->first_invite_user_id), true);
		if($this->last_invite_date != null && !in_array($this->last_invite_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.last_invite_date)', date('Y-m-d', strtotime($this->last_invite_date)));
		$criteria->compare('t.last_invite_user_id', strtolower($this->last_invite_user_id), true);

		if(!isset($_GET['ViewUserNewsletter_sort']))
			$criteria->order = 't.newsletter_id DESC';

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
			$this->templateColumns['newsletter_id'] = array(
				'name' => 'newsletter_id',
				'value' => '$data->newsletter_id',
			);
			$this->templateColumns['user_id'] = array(
				'name' => 'user_id',
				'value' => '$data->user_id',
			);
			$this->templateColumns['register'] = array(
				'name' => 'register',
				'value' => '$data->register',
			);
			$this->templateColumns['register_date'] = array(
				'name' => 'register_date',
				'value' => '$data->register_date',
			);
			$this->templateColumns['invite_by'] = array(
				'name' => 'invite_by',
				'value' => '$data->invite_by',
			);
			$this->templateColumns['invites'] = array(
				'name' => 'invites',
				'value' => '$data->invites',
			);
			$this->templateColumns['invite_all'] = array(
				'name' => 'invite_all',
				'value' => '$data->invite_all',
			);
			$this->templateColumns['invite_users'] = array(
				'name' => 'invite_users',
				'value' => '$data->invite_users',
			);
			$this->templateColumns['invite_user_all'] = array(
				'name' => 'invite_user_all',
				'value' => '$data->invite_user_all',
			);
			$this->templateColumns['first_invite_date'] = array(
				'name' => 'first_invite_date',
				'value' => '!in_array($data->first_invite_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->first_invite_date) : \'-\'',
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
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->templateColumns['first_invite_user_id'] = array(
				'name' => 'first_invite_user_id',
				'value' => '$data->first_invite_user_id',
			);
			$this->templateColumns['last_invite_date'] = array(
				'name' => 'last_invite_date',
				'value' => '!in_array($data->last_invite_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->last_invite_date) : \'-\'',
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
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->templateColumns['last_invite_user_id'] = array(
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