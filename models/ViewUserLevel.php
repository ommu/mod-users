<?php
/**
 * ViewUserLevel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 25 July 2018, 11:48 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_user_level".
 *
 * The followings are the available columns in table '_user_level':
 * @property integer $level_id
 * @property string $user_active
 * @property string $user_pending
 * @property string $user_noverified
 * @property string $user_blocked
 * @property integer $user_all
 */

class ViewUserLevel extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewUserLevel the static model class
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
		return $matches[1].'._user_level';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'level_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('level_id, user_all', 'numerical', 'integerOnly'=>true),
			array('level_id', 'safe'),
			array('user_all', 'length', 'max'=>21),
			array('user_active, user_pending, user_noverified, user_blocked', 'length', 'max'=>23),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('level_id, user_active, user_pending, user_noverified, user_blocked, user_all', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'level_id' => Yii::t('attribute', 'Level'),
			'user_active' => Yii::t('attribute', 'User Active'),
			'user_pending' => Yii::t('attribute', 'User Pending'),
			'user_noverified' => Yii::t('attribute', 'User Noverified'),
			'user_blocked' => Yii::t('attribute', 'User Blocked'),
			'user_all' => Yii::t('attribute', 'User All'),
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
		$criteria->compare('t.level_id', $this->level_id);
		$criteria->compare('t.user_active', $this->user_active);
		$criteria->compare('t.user_pending', $this->user_pending);
		$criteria->compare('t.user_noverified', $this->user_noverified);
		$criteria->compare('t.user_blocked', $this->user_blocked);
		$criteria->compare('t.user_all', $this->user_all);

		if(!Yii::app()->getRequest()->getParam('ViewUserLevel_sort'))
			$criteria->order = 't.level_id DESC';

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
			$this->templateColumns['level_id'] = array(
				'name' => 'level_id',
				'value' => '$data->level_id',
			);
			$this->templateColumns['user_active'] = array(
				'name' => 'user_active',
				'value' => '$data->user_active',
			);
			$this->templateColumns['user_pending'] = array(
				'name' => 'user_pending',
				'value' => '$data->user_pending',
			);
			$this->templateColumns['user_noverified'] = array(
				'name' => 'user_noverified',
				'value' => '$data->user_noverified',
			);
			$this->templateColumns['user_blocked'] = array(
				'name' => 'user_blocked',
				'value' => '$data->user_blocked',
			);
			$this->templateColumns['user_all'] = array(
				'name' => 'user_all',
				'value' => '$data->user_all',
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