<?php
/**
 * ViewUserForgot
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 3 August 2017, 14:21 WIB
 * @modified date 24 July 2018, 07:28 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_user_forgot".
 *
 * The followings are the available columns in table '_user_forgot':
 * @property integer $forgot_id
 * @property integer $expired
 * @property integer $forgot_day_left
 * @property integer $forgot_hour_left
 */

class ViewUserForgot extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewUserForgot the static model class
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
		return $matches[1].'._user_forgot';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'forgot_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('forgot_id, expired, forgot_day_left, forgot_hour_left', 'numerical', 'integerOnly'=>true),
			array('expired, forgot_day_left, forgot_hour_left', 'safe'),
			array('forgot_id', 'length', 'max'=>11),
			array('forgot_day_left, forgot_hour_left', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('forgot_id, expired, forgot_day_left, forgot_hour_left', 'safe', 'on'=>'search'),
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
			'forgot_id' => Yii::t('attribute', 'Forgot'),
			'expired' => Yii::t('attribute', 'Expired'),
			'forgot_day_left' => Yii::t('attribute', 'Forgot Day Left'),
			'forgot_hour_left' => Yii::t('attribute', 'Forgot Hour Left'),
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
		$criteria->compare('t.forgot_id', $this->forgot_id);
		$criteria->compare('t.expired', $this->expired);
		$criteria->compare('t.forgot_day_left', $this->forgot_day_left);
		$criteria->compare('t.forgot_hour_left', $this->forgot_hour_left);

		if(!Yii::app()->getRequest()->getParam('ViewUserForgot_sort'))
			$criteria->order = 't.forgot_id DESC';

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
			$this->templateColumns['forgot_id'] = array(
				'name' => 'forgot_id',
				'value' => '$data->forgot_id',
			);
			$this->templateColumns['expired'] = array(
				'name' => 'expired',
				'value' => '$data->expired',
			);
			$this->templateColumns['forgot_day_left'] = array(
				'name' => 'forgot_day_left',
				'value' => '$data->forgot_day_left',
			);
			$this->templateColumns['forgot_hour_left'] = array(
				'name' => 'forgot_hour_left',
				'value' => '$data->forgot_hour_left',
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