<?php
/**
 * UserLevel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 25 July 2018, 11:48 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_level".
 *
 * The followings are the available columns in table 'ommu_user_level':
 * @property integer $level_id
 * @property integer $name
 * @property integer $desc
 * @property integer $default
 * @property integer $signup
 * @property integer $message_allow
 * @property string $message_limit
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
 * @property string $photo_size
 * @property string $photo_exts
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property ViewUserLevel $view
 * @property Users[] $users
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 */

class UserLevel extends OActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;
	use FileTrait;

	public $gridForbiddenColumn = array();
	public $name_i;
	public $desc_i;
	public $user_i;

	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * Behaviors for this model
	 */
	public function behaviors() 
	{
		return array(
			'sluggable' => array(
				'class'=>'ext.yii-sluggable.SluggableBehavior',
				'columns' => array('title.message'),
				'unique' => true,
				'update' => true,
			),
		);
	}

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
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.ommu_user_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name_i, desc_i', 'required', 'on'=>'info'),
			array('profile_block, profile_search, profile_privacy, profile_comments, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_size, photo_exts', 'required', 'on'=>'user'),
			array('message_allow, message_limit', 'required', 'on'=>'message'),
			array('name, desc, default, signup, message_allow, profile_block, profile_search, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('default, signup, message_allow, message_limit, profile_block, profile_search, profile_privacy, profile_comments, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_size, photo_exts', 'safe'),
			array('name, desc, creation_id, modified_id', 'length', 'max'=>11),
			array('slug, name_i', 'length', 'max'=>32),
			array('desc_i', 'length', 'max'=>128),
			// array('message_limit, profile_privacy, profile_comments, photo_size, photo_exts', 'serialize'),
			// array('creation_date, modified_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('level_id, name, desc, default, signup, message_allow, message_limit, profile_block, profile_search, profile_privacy, profile_comments, profile_style, profile_style_sample, profile_status, profile_invisible, profile_views, profile_change, profile_delete, photo_allow, photo_size, photo_exts, creation_date, creation_id, modified_date, modified_id, slug,
				name_i, desc_i, user_i, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'users' => array(self::HAS_MANY, 'Users', 'level_id'),
			'title' => array(self::BELONGS_TO, 'SourceMessage', 'name'),
			'description' => array(self::BELONGS_TO, 'SourceMessage', 'desc'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
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
			'user_i' => Yii::t('attribute', 'Users'),
			'creation_search' => Yii::t('attribute', 'Creation'),
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
			'title' => array(
				'alias' => 'title',
				'select' => 'message',
			),
			'description' => array(
				'alias' => 'description',
				'select' => 'message',
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.level_id', $this->level_id);
		$criteria->compare('t.name', $this->name);
		$criteria->compare('t.desc', $this->desc);
		$criteria->compare('t.default', Yii::app()->getRequest()->getParam('default') ? Yii::app()->getRequest()->getParam('default') : $this->default);
		$criteria->compare('t.signup', Yii::app()->getRequest()->getParam('signup') ? Yii::app()->getRequest()->getParam('signup') : $this->signup);
		$criteria->compare('t.message_allow', Yii::app()->getRequest()->getParam('message_allow') ? Yii::app()->getRequest()->getParam('message_allow') : $this->message_allow);
		$criteria->compare('t.message_limit', strtolower($this->message_limit), true);
		$criteria->compare('t.profile_block', Yii::app()->getRequest()->getParam('profile_block') ? Yii::app()->getRequest()->getParam('profile_block') : $this->profile_block);
		$criteria->compare('t.profile_search', Yii::app()->getRequest()->getParam('profile_search') ? Yii::app()->getRequest()->getParam('profile_search') : $this->profile_search);
		$criteria->compare('t.profile_privacy', strtolower($this->profile_privacy), true);
		$criteria->compare('t.profile_comments', strtolower($this->profile_comments), true);
		$criteria->compare('t.profile_style', Yii::app()->getRequest()->getParam('profile_style') ? Yii::app()->getRequest()->getParam('profile_style') : $this->profile_style);
		$criteria->compare('t.profile_style_sample', Yii::app()->getRequest()->getParam('profile_style_sample') ? Yii::app()->getRequest()->getParam('profile_style_sample') : $this->profile_style_sample);
		$criteria->compare('t.profile_status', Yii::app()->getRequest()->getParam('profile_status') ? Yii::app()->getRequest()->getParam('profile_status') : $this->profile_status);
		$criteria->compare('t.profile_invisible', Yii::app()->getRequest()->getParam('profile_invisible') ? Yii::app()->getRequest()->getParam('profile_invisible') : $this->profile_invisible);
		$criteria->compare('t.profile_views', Yii::app()->getRequest()->getParam('profile_views') ? Yii::app()->getRequest()->getParam('profile_views') : $this->profile_views);
		$criteria->compare('t.profile_change', Yii::app()->getRequest()->getParam('profile_change') ? Yii::app()->getRequest()->getParam('profile_change') : $this->profile_change);
		$criteria->compare('t.profile_delete', Yii::app()->getRequest()->getParam('profile_delete') ? Yii::app()->getRequest()->getParam('profile_delete') : $this->profile_delete);
		$criteria->compare('t.photo_allow', Yii::app()->getRequest()->getParam('photo_allow') ? Yii::app()->getRequest()->getParam('photo_allow') : $this->photo_allow);
		$criteria->compare('t.photo_size', strtolower($this->photo_size), true);
		$criteria->compare('t.photo_exts', strtolower($this->photo_exts), true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation') ? Yii::app()->getRequest()->getParam('creation') : $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		$criteria->compare('t.slug', strtolower($this->slug), true);

		$criteria->compare('title.message', strtolower($this->name_i), true);
		$criteria->compare('description.message', strtolower($this->desc_i), true);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.user_active', $this->user_i);

		if(!Yii::app()->getRequest()->getParam('UserLevel_sort'))
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
			$this->templateColumns['name_i'] = array(
				'name' => 'name_i',
				'value' => '$data->title->message',
			);
			$this->templateColumns['desc_i'] = array(
				'name' => 'desc_i',
				'value' => '$data->description->message',
			);
			$this->templateColumns['message_limit'] = array(
				'name' => 'message_limit',
				'value' => '$data->message_limit',
			);
			$this->templateColumns['profile_privacy'] = array(
				'name' => 'profile_privacy',
				'value' => '$data->profile_privacy',
			);
			$this->templateColumns['profile_comments'] = array(
				'name' => 'profile_comments',
				'value' => '$data->profile_comments',
			);
			$this->templateColumns['photo_size'] = array(
				'name' => 'photo_size',
				'value' => '$data->photo_size',
			);
			$this->templateColumns['photo_exts'] = array(
				'name' => 'photo_exts',
				'value' => '$data->photo_exts',
			);
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
			if(!Yii::app()->getRequest()->getParam('creation')) {
				$this->templateColumns['creation_search'] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname ? $data->creation->displayname : \'-\'',
				);
			}
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
			$this->templateColumns['slug'] = array(
				'name' => 'slug',
				'value' => '$data->slug',
			);
			$this->templateColumns['user_i'] = array(
				'name' => 'user_i',
				'value' => 'CHtml::link($data->view->user_active ? $data->view->user_active : 0, $data->level_id != 1 ? Yii::app()->controller->createUrl(\'o/member/manage\', array(\'level\'=>$data->level_id)) : Yii::app()->controller->createUrl(\'o/admin/manage\', array(\'level\'=>$data->level_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['message_allow'] = array(
				'name' => 'message_allow',
				'value' => '$data->message_allow ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_block'] = array(
				'name' => 'profile_block',
				'value' => '$data->profile_block ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_search'] = array(
				'name' => 'profile_search',
				'value' => '$data->profile_search ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_style'] = array(
				'name' => 'profile_style',
				'value' => '$data->profile_style ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_style_sample'] = array(
				'name' => 'profile_style_sample',
				'value' => '$data->profile_style_sample ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_status'] = array(
				'name' => 'profile_status',
				'value' => '$data->profile_status ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_invisible'] = array(
				'name' => 'profile_invisible',
				'value' => '$data->profile_invisible ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_views'] = array(
				'name' => 'profile_views',
				'value' => '$data->profile_views ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_change'] = array(
				'name' => 'profile_change',
				'value' => '$data->profile_change ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['profile_delete'] = array(
				'name' => 'profile_delete',
				'value' => '$data->profile_delete ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['photo_allow'] = array(
				'name' => 'photo_allow',
				'value' => '$data->photo_allow ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['default'] = array(
				'name' => 'default',
				//'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'default\', array(\'id\'=>$data->level_id)), $data->default, \'Default,No\')',
				'value' => '$data->default == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['signup'] = array(
				'name' => 'signup',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'signup\', array(\'id\'=>$data->level_id)), $data->signup, \'Enable,Disable#trigger[insert,update]\')',
				//'value' => '$data->signup == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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

	//get Default
	public static function getDefault() 
	{
		$model = self::model()->findByAttributes(array('default' => 1));
		return $model->level_id;
	}

	/**
	 * function getUserLevel
	 */
	public static function getUserLevel($type=null, $array=true) 
	{
		$criteria=new CDbCriteria;
		if($type != null && $type == 'member')
			$criteria->addNotInCondition('t.level_id', array(1));
		if($type != null && $type == 'admin')
			$criteria->compare('t.level_id',1);

		$model = self::model()->findAll($criteria);

		if($array == true) {
			$items = array();
			if($model != null) {
				foreach($model as $key => $val) {
					$items[$val->level_id] = $val->title->message;
				}
				return $items;
			} else
				return false;
		} else
			return $model;
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		$this->name_i = $this->title->message;
		$this->desc_i = $this->description->message;
		$this->message_limit = unserialize($this->message_limit);
		$this->profile_privacy = unserialize($this->profile_privacy);
		$this->profile_comments = unserialize($this->profile_comments);
		$this->photo_size = unserialize($this->photo_size);
		$photo_exts = unserialize($this->photo_exts);
		if(!empty($photo_exts))
			$this->photo_exts = $this->formatFileType($photo_exts, false);

		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
		
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
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

		$location = $this->urlTitle($module.' '.$controller);
		
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && !$this->name)) {
				$name=new SourceMessage;
				$name->message = $this->name_i;
				$name->location = $location.'_title';
				if($name->save())
					$this->name = $name->id;

				$this->slug = $this->urlTitle($this->name_i);

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
				$this->photo_exts = serialize($this->formatFileType($this->photo_exts));
				
			} else if($currentAction == 'o/level/message')
				$this->message_limit = serialize($this->message_limit);
		}
		return true;
	}
}