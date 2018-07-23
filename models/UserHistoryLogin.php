<?php
/**
 * UserHistoryLogin
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 23 July 2018, 22:36 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_history_login".
 *
 * The followings are the available columns in table 'ommu_user_history_login':
 * @property integer $id
 * @property integer $user_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 *
 * The followings are the available model relations:
 * @property Users $user
 */

class UserHistoryLogin extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $level_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserHistoryLogin the static model class
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
		return $matches[1].'.ommu_user_history_login';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lastlogin_date, lastlogin_ip, lastlogin_from', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('user_id', 'safe'),
			array('user_id', 'length', 'max'=>11),
			array('lastlogin_ip, lastlogin_from', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, lastlogin_date, lastlogin_ip, lastlogin_from,
				level_search, user_search', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('attribute', 'ID'),
			'user_id' => Yii::t('attribute', 'User'),
			'lastlogin_date' => Yii::t('attribute', 'Lastlogin Date'),
			'lastlogin_ip' => Yii::t('attribute', 'Lastlogin Ip'),
			'lastlogin_from' => Yii::t('attribute', 'Lastlogin From'),
			'level_search' => Yii::t('attribute', 'level'),
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
				'select' => 'level_id, displayname'
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		if($this->lastlogin_date != null && !in_array($this->lastlogin_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.lastlogin_date)', date('Y-m-d', strtotime($this->lastlogin_date)));
		$criteria->compare('t.lastlogin_ip', strtolower($this->lastlogin_ip), true);
		$criteria->compare('t.lastlogin_from', strtolower($this->lastlogin_from), true);

		$criteria->compare('user.level_id', $this->level_search);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);			//user.displayname

		if(!Yii::app()->getRequest()->getParam('UserHistoryLogin_sort'))
			$criteria->order = 't.id DESC';

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
				$this->templateColumns['level_search'] = array(
					'name' => 'level_search',
					'value' => '$data->user->level->title->message',
					'filter' => UserLevel::getUserLevel(),
					'type' => 'raw',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
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
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			$this->lastlogin_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}