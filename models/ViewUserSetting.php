<?php
/**
 * ViewUserSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 3 August 2017, 14:34 WIB
 * @modified date 12 September 2018, 12:39 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_user_setting".
 *
 * The followings are the available columns in table '_user_setting':
 * @property integer $id
 * @property integer $forgot_difference_hours
 * @property integer $verify_difference_hours
 * @property integer $invite_difference_hours
 */

class ViewUserSetting extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewUserSetting the static model class
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
		return $matches[1].'._user_setting';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, forgot_difference_hours, verify_difference_hours, invite_difference_hours', 'numerical', 'integerOnly'=>true),
			array('id, forgot_difference_hours, verify_difference_hours, invite_difference_hours', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, forgot_difference_hours, verify_difference_hours, invite_difference_hours', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('attribute', 'ID'),
			'forgot_difference_hours' => Yii::t('attribute', 'Forgot Difference Hours'),
			'verify_difference_hours' => Yii::t('attribute', 'Verify Difference Hours'),
			'invite_difference_hours' => Yii::t('attribute', 'Invite Difference Hours'),
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
		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.forgot_difference_hours', $this->forgot_difference_hours);
		$criteria->compare('t.verify_difference_hours', $this->verify_difference_hours);
		$criteria->compare('t.invite_difference_hours', $this->invite_difference_hours);

		if(!Yii::app()->getRequest()->getParam('ViewUserSetting_sort'))
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
			$this->templateColumns['id'] = array(
				'name' => 'id',
				'value' => '$data->id',
			);
			$this->templateColumns['forgot_difference_hours'] = array(
				'name' => 'forgot_difference_hours',
				'value' => '$data->forgot_difference_hours',
			);
			$this->templateColumns['verify_difference_hours'] = array(
				'name' => 'verify_difference_hours',
				'value' => '$data->verify_difference_hours',
			);
			$this->templateColumns['invite_difference_hours'] = array(
				'name' => 'invite_difference_hours',
				'value' => '$data->invite_difference_hours',
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