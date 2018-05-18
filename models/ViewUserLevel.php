<?php
/**
 * ViewUserLevel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_view_user_level".
 *
 * The followings are the available columns in table '_view_user_level':
 * @property integer $level_id
 * @property string $users
 * @property string $user_pending
 * @property string $user_noverified
 * @property string $user_blocked
 * @property string $user_all
 */
class ViewUserLevel extends CActiveRecord
{
	public $defaultColumns = array();
	public $templateColumns = array();
	public $gridForbiddenColumn = array();

	// Variable Search

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
		return $matches[1].'._view_user_level';
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
			array('level_id', 'numerical', 'integerOnly'=>true),
			array('users, user_pending, user_noverified, user_blocked', 'length', 'max'=>23),
			array('user_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('level_id, users, user_pending, user_noverified, user_blocked, user_all', 'safe', 'on'=>'search'),
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
			'users' => Yii::t('attribute', 'Users'),
			'user_pending' => Yii::t('attribute', 'Pending'),
			'user_noverified' => Yii::t('attribute', 'No Verified'),
			'user_blocked' => Yii::t('attribute', 'Blocked'),
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
		$criteria->compare('t.users', strtolower($this->users), true);
		$criteria->compare('t.user_pending', strtolower($this->user_pending), true);
		$criteria->compare('t.user_noverified', strtolower($this->user_noverified), true);
		$criteria->compare('t.user_blocked', strtolower($this->user_blocked), true);
		$criteria->compare('t.user_all', strtolower($this->user_all), true);

		if(!isset($_GET['ViewUserLevel_sort']))
			$criteria->order = 't.level_id DESC';

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
			$this->templateColumns['level_id'] = array(
				'name' => 'level_id',
				'value' => '$data->level_id',
			);
			$this->templateColumns['users'] = array(
				'name' => 'users',
				'value' => '$data->users',
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