<?php
/**
 * UserInviteQueue
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
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
 * This is the model class for table "ommu_user_invite_queue".
 *
 * The followings are the available columns in table 'ommu_user_invite_queue':
 * @property string $queue_id
 * @property integer $publish
 * @property string $reference_id
 * @property string $displayname
 * @property string $email
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property UserInvites[] $UserInvites
 */
class UserInviteQueue extends CActiveRecord
{
	public $defaultColumns = array();
	
	// Variable Search
	public $reference_search;
	public $creation_search;
	public $modified_search;
	public $register_search;
	public $user_search;
	public $invite_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserInviteQueue the static model class
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
		return 'ommu_user_invite_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'required'),
			array('queue_id, publish, reference_id, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('queue_id, reference_id, creation_id, modified_id', 'length', 'max'=>11),
			array('email', 'length', 'max'=>32),
			array('displayname', 'length', 'max'=>64),
			array('email', 'email'),
			array('displayname', 'safe', 'on'=>'search'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('queue_id, publish, reference_id, displayname, email, creation_date, creation_id, modified_date, modified_id, updated_date,
				reference_search, creation_search, modified_search, register_search, user_search, invite_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewUserInviteQueue', 'queue_id'),
			'reference' => array(self::BELONGS_TO, 'Users', 'reference_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'invites' => array(self::HAS_MANY, 'UserInvites', 'queue_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'queue_id' => Yii::t('attribute', 'Queue'),
			'publish' => Yii::t('attribute', 'Publish'),
			'reference_id' => Yii::t('attribute', 'Reference'),
			'displayname' => Yii::t('attribute', 'Displayname'),
			'email' => Yii::t('attribute', 'Email'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'reference_search' => Yii::t('attribute', 'Reference'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'register_search' => Yii::t('attribute', 'Register'),
			'user_search' => Yii::t('attribute', 'User'),
			'invite_search' => Yii::t('attribute', 'Invites'),
			'register_name_i' => Yii::t('attribute', 'Register Name'),
			'register_date_i' => Yii::t('attribute', 'Register Date'),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias'=>'view',
			),
			'view.user' => array(
				'alias'=>'view_user',
				'select'=>'level_id, displayname'
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.queue_id',$this->queue_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish')
			$criteria->compare('t.publish',1);
		elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish')
			$criteria->compare('t.publish',0);
		elseif(isset($_GET['type']) && $_GET['type'] == 'trash')
			$criteria->compare('t.publish',2);
		else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('t.reference_id',$this->reference_id);
		$criteria->compare('t.displayname',strtolower($this->displayname),true);
		$criteria->compare('t.email',strtolower($this->email),true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.updated_date)',date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('reference.displayname',strtolower($this->reference_search),true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search),true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);
		$criteria->compare('view.register',$this->register_search);
		if(isset($_GET['user']))
			$criteria->compare('view.user_id',$_GET['user']);
		if($this->view->user_id)
			$criteria->compare('view_user.displayname',strtolower($this->user_search),true);
		else
			$criteria->compare('t.displayname',strtolower($this->user_search),true);
		$criteria->compare('view.invites',$this->invite_search);
		
		if(!isset($_GET['UserInviteQueue_sort']))
			$criteria->order = 't.queue_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
		}else {
			//$this->defaultColumns[] = 'queue_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'reference_id';
			$this->defaultColumns[] = 'displayname';
			$this->defaultColumns[] = 'email';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'updated_date';
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
			if(!isset($_GET['user'])) {
				$this->defaultColumns[] = array(
					'name' => 'user_search',
					'value' => '$data->view->user_id ? $data->view->user->displayname : ($data->displayname ? $data->displayname : \'-\')',
				);
			}
			$this->defaultColumns[] = 'email';
			if(!isset($_GET['reference'])) {
				$this->defaultColumns[] = array(
					'name' => 'reference_search',
					'value' => '$data->reference_id ? $data->reference->displayname : \'-\'',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
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
			$this->defaultColumns[] = array(
				'name' => 'invite_search',
				'value' => 'CHtml::link($data->view->invites ? $data->view->invites : 0, Yii::app()->controller->createUrl("o/invite/manage",array("queue"=>$data->queue_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'register_search',
				'value' => '$data->view->register == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\',array(\'id\'=>$data->queue_id)), $data->publish)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
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
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$this->email = strtolower($this->email);
		}
		return true;	
	}
}