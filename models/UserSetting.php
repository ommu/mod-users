<?php
/**
 * UserSetting
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 4 August 2017, 17:33 WIB
 * @modified date 12 September 2018, 12:39 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_setting".
 *
 * The followings are the available columns in table 'ommu_user_setting':
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $forgot_diff_type
 * @property integer $forgot_difference
 * @property string $verify_diff_type
 * @property integer $verify_difference
 * @property string $invite_diff_type
 * @property integer $invite_difference
 * @property string $invite_order
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property ViewUserSetting $view
 * @property Users $modified
 */

class UserSetting extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserSetting the static model class
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
		return $matches[1].'.ommu_user_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('license, permission, meta_keyword, meta_description, forgot_difference, verify_difference, invite_difference, invite_order', 'required'),
			array('permission, forgot_difference, verify_difference, invite_difference, modified_id', 'numerical', 'integerOnly'=>true),
			array('forgot_diff_type, verify_diff_type, invite_diff_type', 'safe'),
			array('forgot_diff_type, verify_diff_type, invite_diff_type', 'length', 'max'=>1),
			array('invite_order', 'length', 'max'=>4),
			array('modified_id', 'length', 'max'=>11),
			array('license', 'length', 'max'=>32),
			// array('modified_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, license, permission, meta_keyword, meta_description, forgot_diff_type, forgot_difference, verify_diff_type, verify_difference, invite_diff_type, invite_difference, invite_order, modified_date, modified_id,
				modified_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewUserSetting', 'id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'license' => Yii::t('attribute', 'License'),
			'permission' => Yii::t('attribute', 'Permission'),
			'meta_keyword' => Yii::t('attribute', 'Meta Keyword'),
			'meta_description' => Yii::t('attribute', 'Meta Description'),
			'forgot_diff_type' => Yii::t('attribute', 'Forgot Diff Type'),
			'forgot_difference' => Yii::t('attribute', 'Forgot Difference'),
			'verify_diff_type' => Yii::t('attribute', 'Verify Diff Type'),
			'verify_difference' => Yii::t('attribute', 'Verify Difference'),
			'invite_diff_type' => Yii::t('attribute', 'Invite Diff Type'),
			'invite_difference' => Yii::t('attribute', 'Invite Difference'),
			'invite_order' => Yii::t('attribute', 'Invite Order'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
			'view' => array(
				'alias' => 'view',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.license', strtolower($this->license), true);
		$criteria->compare('t.permission', $this->permission);
		$criteria->compare('t.meta_keyword', strtolower($this->meta_keyword), true);
		$criteria->compare('t.meta_description', strtolower($this->meta_description), true);
		$criteria->compare('t.forgot_diff_type', $this->forgot_diff_type);
		$criteria->compare('t.forgot_difference', $this->forgot_difference);
		$criteria->compare('t.verify_diff_type', $this->verify_diff_type);
		$criteria->compare('t.verify_difference', $this->verify_difference);
		$criteria->compare('t.invite_diff_type', $this->invite_diff_type);
		$criteria->compare('t.invite_difference', $this->invite_difference);
		$criteria->compare('t.invite_order', strtolower($this->invite_order), true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);

		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('UserSetting_sort'))
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
			$this->templateColumns['license'] = array(
				'name' => 'license',
				'value' => '$data->license',
			);
			$this->templateColumns['permission'] = array(
				'name' => 'permission',
				'value' => '$data->permission ? Yii::t(\'phrase\', \'Yes\') : Yii::t(\'phrase\, \'No\')',
			);
			$this->templateColumns['meta_keyword'] = array(
				'name' => 'meta_keyword',
				'value' => '$data->meta_keyword',
			);
			$this->templateColumns['meta_description'] = array(
				'name' => 'meta_description',
				'value' => '$data->meta_description',
			);
			$this->templateColumns['forgot_diff_type'] = array(
				'name' => 'forgot_diff_type',
				'value' => '$data->forgot_diff_type',
			);
			$this->templateColumns['forgot_difference'] = array(
				'name' => 'forgot_difference',
				'value' => '$data->forgot_difference',
			);
			$this->templateColumns['verify_diff_type'] = array(
				'name' => 'verify_diff_type',
				'value' => '$data->verify_diff_type',
			);
			$this->templateColumns['verify_difference'] = array(
				'name' => 'verify_difference',
				'value' => '$data->verify_difference',
			);
			$this->templateColumns['invite_diff_type'] = array(
				'name' => 'invite_diff_type',
				'value' => '$data->invite_diff_type',
			);
			$this->templateColumns['invite_difference'] = array(
				'name' => 'invite_difference',
				'value' => '$data->invite_difference',
			);
			$this->templateColumns['invite_order'] = array(
				'name' => 'invite_order',
				'value' => '$data->invite_order',
			);
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->modified_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'modified_date'),
			);
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
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
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
		}
		return true;
	}
}