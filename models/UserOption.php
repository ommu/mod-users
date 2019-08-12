<?php
/**
 * UserOption
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 25 July 2018, 05:49 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_option".
 *
 * The followings are the available columns in table 'ommu_user_option':
 * @property integer $option_id
 * @property integer $ommu_status
 * @property integer $invite_limit
 * @property integer $invite_success
 * @property string $signup_from
 *
 * The followings are the available model relations:
 * @property Users $option
 */

class UserOption extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $option_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserOption the static model class
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
		return $matches[1].'.ommu_user_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('signup_from', 'required'),
			array('option_id, ommu_status, invite_limit, invite_success', 'numerical', 'integerOnly'=>true),
			array('ommu_status, invite_limit, invite_success', 'safe'),
			array('option_id', 'length', 'max'=>11),
			// array('option_id', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('option_id, ommu_status, invite_limit, invite_success, signup_from,
				option_search', 'safe', 'on'=>'search'),
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
			'option' => array(self::BELONGS_TO, 'Users', 'option_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'option_id' => Yii::t('attribute', 'Option'),
			'ommu_status' => Yii::t('attribute', 'Ommu Status'),
			'invite_limit' => Yii::t('attribute', 'Invite Limit'),
			'invite_success' => Yii::t('attribute', 'Invite Success'),
			'signup_from' => Yii::t('attribute', 'Signup From'),
			'option_search' => Yii::t('attribute', 'Option'),
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
			'option' => array(
				'alias' => 'option',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.option_id', Yii::app()->getRequest()->getParam('option') ? Yii::app()->getRequest()->getParam('option') : $this->option_id);
		$criteria->compare('t.ommu_status', Yii::app()->getRequest()->getParam('ommu_status') ? Yii::app()->getRequest()->getParam('ommu_status') : $this->ommu_status);
		$criteria->compare('t.invite_limit', $this->invite_limit);
		$criteria->compare('t.invite_success', $this->invite_success);
		$criteria->compare('t.signup_from', strtolower($this->signup_from), true);

		$criteria->compare('option.displayname', strtolower($this->option_search), true);			//option.displayname

		if(!Yii::app()->getRequest()->getParam('UserOption_sort'))
			$criteria->order = 't.option_id DESC';

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
			$this->templateColumns['invite_limit'] = array(
				'name' => 'invite_limit',
				'value' => '$data->invite_limit',
			);
			$this->templateColumns['invite_success'] = array(
				'name' => 'invite_success',
				'value' => '$data->invite_success',
			);
			$this->templateColumns['signup_from'] = array(
				'name' => 'signup_from',
				'value' => '$data->signup_from',
			);
			$this->templateColumns['ommu_status'] = array(
				'name' => 'ommu_status',
				'value' => '$data->ommu_status ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
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