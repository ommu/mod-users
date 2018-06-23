<?php
/**
 * UserLevel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-users
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
 * This is the model class for table "ommu_user_level".
 *
 * The followings are the available columns in table 'ommu_user_level':
 * @property integer $level_id
 * @property string $name
 * @property string $desc
 * @property integer $default
 * @property integer $signup
 * @property integer $message_allow
 * @property integer $message_limit
 * @property integer $profile_block
 * @property integer $profile_search
 * @property string $profile_privacy
 * @property string $profile_comments
 * @property integer $profile_style
 * @property integer $profile_style_sample
 * @property integer $profile_status
 * @property integer $profile_invisible
 * @property integer $profile_views
 * @property integer $profile_change
 * @property integer $profile_delete
 * @property integer $photo_allow
 * @property integer $photo_size
 * @property string $photo_exts
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property Users[] $Users
 */
class UserLevel extends CActiveRecord
{
	public $defaultColumns = array();
	public $name_i;
	public $desc_i;
	
	// Variable Search
	public $creation_search;
	public $modified_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserLevel the static model class
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
		return 'ommu_user_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('
				name_i, desc_i', 'required', 'on'=>'info'),
			array('profile_block, profile_search, profile_privacy, profile_comments, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_size, photo_exts', 'required', 'on'=>'user'),
			array('message_allow, message_limit', 'required', 'on'=>'message'),
			array('default, signup, message_allow, profile_block, profile_search, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow', 'numerical', 'integerOnly'=>true),
			array('name, desc, creation_id, modified_id', 'length', 'max'=>11),
			array('
				name_i', 'length', 'max'=>32),
			array('', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('level_id, name, desc, default, signup, message_allow, message_limit, profile_block, profile_search, profile_privacy, profile_comments, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_size, photo_exts, creation_date, creation_id, modified_date, modified_id,
				name_i, desc_i, creation_search, modified_search, user_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewUserLevel', 'level_id'),
			'title' => array(self::BELONGS_TO, 'SourceMessage', 'name'),
			'description' => array(self::BELONGS_TO, 'SourceMessage', 'desc'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'users' => array(self::HAS_MANY, 'Users', 'level_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'level_id' => Yii::t('attribute', 'Level'),
			'name' => Yii::t('attribute', 'Level'),
			'desc' => Yii::t('attribute', 'Description'),
			'default' => Yii::t('attribute', 'Default'),
			'signup' => Yii::t('attribute', 'Signup'),
			'message_allow' => Yii::t('attribute', 'Can users block other users?'),
			'message_limit' => Yii::t('attribute', 'Message Limit'),
			'profile_block' => Yii::t('attribute', 'Can users block other users?'),
			'profile_search' => Yii::t('attribute', 'Search Privacy Options'),
			'profile_privacy' => Yii::t('attribute', 'Profile Privacy'),
			'profile_comments' => Yii::t('attribute', 'Profile Comments'),
			'profile_style' => Yii::t('attribute', 'Default'),
			'profile_style_sample' => Yii::t('attribute', 'Profile Style Sample'),
			'profile_status' => Yii::t('attribute', 'Allow profile status messages?'),
			'profile_invisible' => Yii::t('attribute', 'Allow users to go invisible?'),
			'profile_views' => Yii::t('attribute', 'Allow users to see who viewed their profile?'),
			'profile_change' => Yii::t('attribute', 'Allow username change?'),
			'profile_delete' => Yii::t('attribute', 'Allow account deletion?'),
			'photo_allow' => Yii::t('attribute', 'Allow User Photos?'),
			'photo_size' => Yii::t('attribute', 'Photo Size'),
			'photo_exts' => Yii::t('attribute', 'Photo Exts'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'name_i' => Yii::t('attribute', 'Level'),
			'desc_i' => Yii::t('attribute', 'Description'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'user_search' => Yii::t('attribute', 'Users'),
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
		
		// Custom Search
		$criteria->with = array(
			'view' => array(
				'alias'=>'view',
			),
			'title' => array(
				'alias'=>'title',
				'select'=>'message',
			),
			'description' => array(
				'alias'=>'description',
				'select'=>'message',
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname',
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.level_id',$this->level_id);
		$criteria->compare('t.name',$this->name);
		$criteria->compare('t.desc',$this->desc);
		$criteria->compare('t.default',$this->default);
		$criteria->compare('t.signup',$this->signup);
		$criteria->compare('t.message_allow',$this->message_allow);
		$criteria->compare('t.message_limit',strtolower($this->message_limit),true);
		$criteria->compare('t.profile_block',$this->profile_block);
		$criteria->compare('t.profile_search',$this->profile_search);
		$criteria->compare('t.profile_privacy',strtolower($this->profile_privacy),true);
		$criteria->compare('t.profile_comments',strtolower($this->profile_comments),true);
		$criteria->compare('t.profile_style',$this->profile_style);
		$criteria->compare('t.profile_style_sample',$this->profile_style_sample);
		$criteria->compare('t.profile_status',$this->profile_status);
		$criteria->compare('t.profile_invisible',$this->profile_invisible);
		$criteria->compare('t.profile_views',$this->profile_views);
		$criteria->compare('t.profile_change',$this->profile_change);
		$criteria->compare('t.profile_delete',$this->profile_delete);
		$criteria->compare('t.photo_allow',$this->photo_allow);
		$criteria->compare('t.photo_size',strtolower($this->photo_size),true);
		$criteria->compare('t.photo_exts',$this->photo_exts,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		$criteria->compare('title.message', strtolower($this->name_i), true);
		$criteria->compare('description.message', strtolower($this->desc_i), true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search),true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);
		$criteria->compare('view.user_active',$this->user_search);

		if(!isset($_GET['UserLevel_sort']))
			$criteria->order = 't.level_id DESC';

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
			//$this->defaultColumns[] = 'level_id';
			$this->defaultColumns[] = 'name';
			$this->defaultColumns[] = 'desc';
			$this->defaultColumns[] = 'default';
			$this->defaultColumns[] = 'signup';
			$this->defaultColumns[] = 'message_allow';
			$this->defaultColumns[] = 'message_limit';
			$this->defaultColumns[] = 'profile_block';
			$this->defaultColumns[] = 'profile_search';
			$this->defaultColumns[] = 'profile_privacy';
			$this->defaultColumns[] = 'profile_comments';
			$this->defaultColumns[] = 'profile_style';
			$this->defaultColumns[] = 'profile_style_sample';
			$this->defaultColumns[] = 'profile_status';
			$this->defaultColumns[] = 'profile_invisible';
			$this->defaultColumns[] = 'profile_views';
			$this->defaultColumns[] = 'profile_change';
			$this->defaultColumns[] = 'profile_delete';
			$this->defaultColumns[] = 'photo_allow';
			$this->defaultColumns[] = 'photo_size';
			$this->defaultColumns[] = 'photo_exts';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
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
				'name' => 'name_i',
				'value' => '$data->title->message',
			);
			$this->defaultColumns[] = array(
				'name' => 'desc_i',
				'value' => '$data->description->message',
			);
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
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
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
				'name' => 'user_search',
				'value' => 'CHtml::link($data->view->user_active ? $data->view->user_active : 0, $data->level_id != 1 ? Yii::app()->controller->createUrl("o/member/manage",array("level"=>$data->level_id)) : Yii::app()->controller->createUrl("o/admin/manage",array("level"=>$data->level_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'default',
				'value' => '$data->default == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Utility::getPublish(Yii::app()->controller->createUrl("default",array("id"=>$data->level_id)), $data->default, 6)',
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

	//get Default
	public static function getDefault() 
	{
		$model = self::model()->findByAttributes(array('default' => 1));
		return $model->level_id;
	}

	//get Type Member (Except administrator)
	public static function getUserLevel($type=null) 
	{
		$criteria=new CDbCriteria;
		if($type != null && $type == 'member')
			$criteria->addNotInCondition('t.level_id',array(1));
		if($type != null && $type == 'admin')
			$criteria->compare('t.level_id',1);
		
		$model = self::model()->findAll($criteria);
		
		$items = array();
		if($model != null) {
			foreach($model as $key => $val)
				$items[$val->level_id] = $val->title->message;
		
			return $items;
		} else
			return false;
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		$this->name_i = $this->title->message;
		$this->desc_i = $this->description->message;
		
		parent::afterFind();
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if($currentAction == 'o/level/user') {
				if($this->photo_size['width'] == '' || $this->photo_size['height'] == '')
					$this->addError('photo_size', Yii::t('phrase', 'Photo Size cannot be blank.'));
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);
		$currentAction = strtolower($controller.'/'.$action);

		$location = $module.' '.$controller;
		
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && !$this->name)) {
				$name=new SourceMessage;
				$name->message = $this->name_i;
				$name->location = $location.'_title';
				if($name->save())
					$this->name = $name->id;
				
				$this->slug = Utility::getUrlTitle($this->name_i);
				
			} else {
				if($currentAction == 'o/level/edit') {
					$name = SourceMessage::model()->findByPk($this->name);
					$name->message = $this->name_i;
					$name->save();
				}
			}
			
			if($this->isNewRecord || (!$this->isNewRecord && !$this->desc)) {
				$desc=new SourceMessage;
				$desc->message = $this->desc_i;
				$desc->location = $location.'_description';
				if($desc->save())
					$this->desc = $desc->id;
				
			} else {
				if($currentAction == 'o/level/edit') {
					$desc = SourceMessage::model()->findByPk($this->desc);
					$desc->message = $this->desc_i;
					$desc->save();
				}
			}

			// set to default modules
			if($this->default == 1) {
				self::model()->updateAll(array(
					'default' => 0,
				));
				$this->default = 1;
			}
				
			if($currentAction == 'o/level/user') {
				$this->profile_privacy = serialize($this->profile_privacy);
				$this->profile_comments = serialize($this->profile_comments);
				$this->photo_size = serialize($this->photo_size);
				$this->photo_exts = serialize(Utility::formatFileType($this->photo_exts));
				
			} else if($currentAction == 'o/level/message')
				$this->message_limit = serialize($this->message_limit);
				
		}
		return true;
	}
}