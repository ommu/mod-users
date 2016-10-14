<?php
/**
 * UserLevel
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Users
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
 * This is the model class for table "ommu_user_level".
 *
 * The followings are the available columns in table 'ommu_user_level':
 * @property integer $level_id
 * @property string $name
 * @property string $desc
 * @property integer $defaults
 * @property integer $signup
 * @property integer $message_allow
 * @property integer $message_inbox
 * @property integer $message_outbox
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
 * @property integer $photo_width
 * @property integer $photo_height
 * @property string $photo_exts
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 */
class UserLevel extends CActiveRecord
{
	public $defaultColumns = array();
	public $title;
	public $description;
	
	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
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
				title, description', 'required', 'on'=>'info'),
			array('profile_block, profile_search, profile_privacy, profile_comments, photo_allow, photo_width, photo_height, photo_exts, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete', 'required', 'on'=>'user'),
			array('message_allow, message_inbox, message_outbox', 'required', 'on'=>'message'),
			array('defaults, signup, message_allow, message_inbox, message_outbox, profile_block, profile_search, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_width, photo_height', 'numerical', 'integerOnly'=>true),
			array('name, desc', 'length', 'max'=>11),
			array('profile_privacy, profile_comments, photo_exts,
				title', 'length', 'max'=>32),
			array('creation_date, params', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('level_id, name, desc, defaults, signup, message_allow, message_inbox, message_outbox, profile_block, profile_search, profile_privacy, profile_comments, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_width, photo_height, photo_exts, creation_date,
				title, description, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'title' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'name'),
			'description' => array(self::BELONGS_TO, 'OmmuSystemPhrase', 'desc'),
			'creation_relation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified_relation' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'view_level' => array(self::BELONGS_TO, 'ViewUserLevel', 'level_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'level_id' => Yii::t('attribute', 'User Levels'),
			'name' => Yii::t('attribute', 'Name'),
			'desc' => Yii::t('attribute', 'Description'),
			'defaults' => Yii::t('attribute', 'Defaults'),
			'signup' => Yii::t('attribute', 'Signup'),
			'message_allow' => Yii::t('attribute', 'Can users block other users?'),
			'message_inbox' => Yii::t('attribute', 'Message Inbox'),
			'message_outbox' => Yii::t('attribute', 'Message Outbox'),
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
			'photo_width' => Yii::t('attribute', 'Photo Width'),
			'photo_height' => Yii::t('attribute', 'Photo Height'),
			'photo_exts' => Yii::t('attribute', 'Photo Exts'),
			'creation_date' => Yii::t('attribute', 'Creation Date',),
			'creation_id' => Yii::t('attribute', 'Creation',),
			'modified_date' => Yii::t('attribute', 'Modified Date',),
			'modified_id' => Yii::t('attribute', 'Modified',),
			'title' => Yii::t('attribute', 'Name'),
			'description' => Yii::t('attribute', 'Description'),
			'creation_search' => Yii::t('attribute', 'Creation',),
			'modified_search' => Yii::t('attribute', 'Modified',),
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

		$criteria->compare('level_id',$this->level_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('defaults',$this->defaults);
		$criteria->compare('signup',$this->signup);
		$criteria->compare('message_allow',$this->message_allow);
		$criteria->compare('message_inbox',$this->message_inbox);
		$criteria->compare('message_outbox',$this->message_outbox);
		$criteria->compare('profile_block',$this->profile_block);
		$criteria->compare('profile_search',$this->profile_search);
		$criteria->compare('profile_privacy',$this->profile_privacy,true);
		$criteria->compare('profile_comments',$this->profile_comments,true);
		$criteria->compare('profile_style',$this->profile_style);
		$criteria->compare('profile_style_sample',$this->profile_style_sample);
		$criteria->compare('profile_status',$this->profile_status);
		$criteria->compare('profile_invisible',$this->profile_invisible);
		$criteria->compare('profile_views',$this->profile_views);
		$criteria->compare('profile_change',$this->profile_change);
		$criteria->compare('profile_delete',$this->profile_delete);
		$criteria->compare('photo_allow',$this->photo_allow);
		$criteria->compare('photo_width',$this->photo_width);
		$criteria->compare('photo_height',$this->photo_height);
		$criteria->compare('photo_exts',$this->photo_exts,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id',$this->modified_id);
		
		// Custom Search
		$criteria->with = array(
			'view_level' => array(
				'alias'=>'view_level',
				'select'=>'level_name, level_desc, users'
			),
			'creation_relation' => array(
				'alias'=>'creation_relation',
				'select'=>'displayname',
			),
			'modified_relation' => array(
				'alias'=>'modified_relation',
				'select'=>'displayname',
			),
		);
		$criteria->compare('view_level.level_name',strtolower($this->title), true);
		$criteria->compare('view_level.level_desc',strtolower($this->description), true);
		$criteria->compare('creation_relation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified_relation.displayname',strtolower($this->modified_search), true);
		
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
		}else {
			//$this->defaultColumns[] = 'level_id';
			$this->defaultColumns[] = 'name';
			$this->defaultColumns[] = 'desc';
			$this->defaultColumns[] = 'defaults';
			$this->defaultColumns[] = 'signup';
			$this->defaultColumns[] = 'message_allow';
			$this->defaultColumns[] = 'message_inbox';
			$this->defaultColumns[] = 'message_outbox';
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
			$this->defaultColumns[] = 'photo_width';
			$this->defaultColumns[] = 'photo_height';
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
				'name' => 'title',
				'value' => 'Phrase::trans($data->name, 2)',
			);
			$this->defaultColumns[] = array(
				'name' => 'description',
				'value' => 'Phrase::trans($data->desc, 2)',
			);
			$this->defaultColumns[] = array(
				'name' => 'users',
				'value' => '$data->level_id != 1 ? CHtml::link($data->view_level->users." ".Yii::t(\'attribute\', \'Users\'), Yii::app()->controller->createUrl("o/member/manage",array("level"=>$data->level_id))) : CHtml::link($data->view_level->users." ".Yii::t(\'attribute\', \'Users\'), Yii::app()->controller->createUrl("o/admin/manage",array("level"=>$data->level_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation_relation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
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
				'name' => 'defaults',
				'value' => '$data->defaults == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Utility::getPublish(Yii::app()->controller->createUrl("default",array("id"=>$data->level_id)), $data->defaults, 6)',
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

	//get Default
	public static function getDefault(){
		$model = self::model()->findByAttributes(array('defaults' => 1));
		return $model->level_id;
	}

	//get Type Member (Except administrator)
	public static function getTypeMember($type=null){
		if($type == null) {
			$model = self::model()->findAll(array(
				'condition'=>'level_id != :level',
				'params' => array(
					':level' => 1,
				),
			));
		} else {
			$model = self::model()->findAll();
		}
		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				$items[$val->level_id] = Phrase::trans($val->name, 2);
			}
			return $items;
		}else {
			return false;
		}
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				$location = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
				$title=new OmmuSystemPhrase;
				$title->location = $location.'_title';
				$title->en_us = $this->title;
				if($title->save())
					$this->name = $title->phrase_id;

				$desc=new OmmuSystemPhrase;
				$desc->location = $location.'_description';
				$desc->en_us = $this->description;
				if($desc->save())
					$this->desc = $desc->phrase_id;
				
				$this->creation_id = Yii::app()->user->id;	
				
			}else {
				$title = OmmuSystemPhrase::model()->findByPk($this->name);
				$title->en_us = $this->title;
				$title->save();

				$desc = OmmuSystemPhrase::model()->findByPk($this->desc);
				$desc->en_us = $this->description;
				$desc->save();

				// set to default modules
				if($this->defaults == 1) {
					self::model()->updateAll(array(
						'defaults' => 0,	
					));
					$this->defaults = 1;
				}
				
				$this->modified_id = Yii::app()->user->id;
			}
		}
		return true;
	}
}