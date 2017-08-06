<?php
/**
 * UserInvites
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
 * This is the model class for table "ommu_user_invites".
 *
 * The followings are the available columns in table 'ommu_user_invites':
 * @property string $invite_id
 * @property integer $publish
 * @property string $queue_id
 * @property string $user_id
 * @property string $code
 * @property integer $invites
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property UserInviteQueue $queue
 */
class UserInvites extends CActiveRecord
{
	public $defaultColumns = array();	
	public $displayname;
	public $email;
	
	// Variable Search
	public $userlevel_search;
	public $user_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserInvites the static model class
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
		return 'ommu_user_invites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, code,
				email', 'required'),
			array('queue_id, user_id, invites, modified_id', 'numerical', 'integerOnly'=>true),
			array('queue_id, user_id, invites, modified_id', 'length', 'max'=>11),
			array('code', 'length', 'max'=>16),
			array('invite_ip', 'length', 'max'=>20),
			array('
				email', 'length', 'max'=>32),
			array('
				displayname', 'length', 'max'=>64),
			array('
				displayname, email', 'email'),
			array('invite_date, invite_ip', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('invite_id, queue_id, user_id, code, invites, invite_date, invite_ip, modified_date, modified_id, updated_date,
				displayname, email, userlevel_search, user_search, modified_search', 'safe', 'on'=>'search'),
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
			'queue' => array(self::BELONGS_TO, 'UserInviteQueue', 'queue_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'histories' => array(self::HAS_MANY, 'UserInviteHistory', 'invite_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'invite_id' => Yii::t('attribute', 'Invite'),
			'publish' => Yii::t('attribute', 'Publish'),
			'queue_id' => Yii::t('attribute', 'Queue'),
			'user_id' => Yii::t('attribute', 'Inviter'),
			'code' => Yii::t('attribute', 'Invite Code'),
			'invites' => Yii::t('attribute', 'Invites'),
			'invite_date' => Yii::t('attribute', 'Invite Date'),
			'invite_ip' => Yii::t('attribute', 'Invite Ip'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'displayname' => Yii::t('attribute', 'Displayname'),
			'email' => Yii::t('attribute', 'Email'),
			'userlevel_search' => Yii::t('attribute', 'Level Inviter'),
			'user_search' => Yii::t('attribute', 'Inviter'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
			'queue' => array(
				'alias'=>'queue',
				'select'=>'queue_id, displayname, email'
			),
			'queue.view' => array(
				'alias'=>'queue_view',
				'select'=>'user_id'
			),
			'queue.view.user' => array(
				'alias'=>'queue_view_user',
				'select'=>'displayname'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'level_id, displayname'
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname'
			),
		);

		$criteria->compare('t.invite_id',$this->invite_id);
		if(isset($_GET['queue']))
			$criteria->compare('t.queue_id',$_GET['queue']);
		else
			$criteria->compare('t.queue_id',$this->queue_id);
		if(isset($_GET['user']))
			$criteria->compare('t.user_id',$_GET['user']);
		else
			$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.code',strtolower($this->code),true);
		$criteria->compare('t.invites',$this->invites);
		if($this->invite_date != null && !in_array($this->invite_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.invite_date)',date('Y-m-d', strtotime($this->invite_date)));
		$criteria->compare('t.invite_ip',strtolower($this->invite_ip),true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.updated_date)',date('Y-m-d', strtotime($this->updated_date)));
		
		if($this->queue->view->user_id)
			$criteria->compare('queue_view_user.displayname',strtolower($this->displayname),true);
		else
			$criteria->compare('queue.displayname',strtolower($this->displayname),true);
		$criteria->compare('queue.email',strtolower($this->email),true);
		$criteria->compare('user.level_id',$this->userlevel_search);
		$criteria->compare('user.displayname',strtolower($this->user_search),true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);

		if(!isset($_GET['UserInvites_sort']))
			$criteria->order = 't.invite_id DESC';

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
			//$this->defaultColumns[] = 'invite_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'queue_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'code';
			$this->defaultColumns[] = 'invites';
			$this->defaultColumns[] = 'invite_date';
			$this->defaultColumns[] = 'invite_ip';
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
			if(!isset($_GET['queue'])) {
				$this->defaultColumns[] = array(
					'name' => 'displayname',
					'value' => '$data->queue->view->user_id ? $data->queue->view->user->displayname : ($data->queue->displayname ? $data->queue->displayname : \'-\')',
				);
				$this->defaultColumns[] = array(
					'name' => 'email',
					'value' => '$data->queue->email',
				);
			}
			if(!isset($_GET['user'])) {
				$this->defaultColumns[] = array(
					'name' => 'user_search',
					'value' => '$data->user_id ? $data->user->displayname : \'-\'',
				);
				$this->defaultColumns[] = array(
					'name' => 'userlevel_search',
					'value' => '$data->user_id ? Phrase::trans($data->user->level->name) : \'-\'',
					'filter'=>UserLevel::getUserLevel(),
					'type' => 'raw',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'invite_date',
				'value' => 'Utility::dateFormat($data->invite_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.components.system.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'invite_date', 
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'invite_date_filter',
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
				'name' => 'invites',
				'value' => 'CHtml::link($data->invites ? $data->invites : 0, Yii::app()->controller->createUrl("o/invitehistory/manage",array("invite"=>$data->invite_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\',array(\'id\'=>$data->invite_id)), $data->publish)',
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
	 * generate invite code
	 */
	public static function getUniqueCode() {
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*1000000);
		$i = 0;
		$salt = '' ;

		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 2);
			$salt = $salt . $tmp; 
			$i++;
		}

		return $salt;
	}

	// Get plugin list
	public static function getInvite($email, $order=null) 
	{
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'queue' => array(
				'alias'=>'queue',
				'select'=>'queue_id, publish, email',
			),
		);
		$criteria->compare('t.publish',1);
		$criteria->compare('t.user_id','<>0');
		$criteria->compare('queue.publish',1);
		$criteria->compare('queue.email',strtolower($email));
		$criteria->order = $order == null || $order == 'DESC' ? 't.invite_id DESC' : 't.invite_id ASC';
		$model = self::model()->find($criteria);
		
		return $model;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$controller = strtolower(Yii::app()->controller->id);
		
		if(parent::beforeValidate()) {			
			if($this->isNewRecord)
				$this->user_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if($this->email != '') {
				$model = UserInviteQueue::model()->findByAttributes(array('email' => strtolower($this->email)), array(
					'select' => 'queue_id, user_id',
				));
				if($model == null) {														// email belum masuk daftar queue
					$queue = new UserInviteQueue;
					if($this->displayname != '')
						$queue->displayname = $this->displayname;
					$queue->email = $this->email;
					if($queue->save())
						$this->queue_id = $queue->queue_id;
					
				} else {																	// email sudah dalam daftar invite
					$this->queue_id = $model->queue_id;
					if(($module != null && $module == 'users') && $controller == 'invite') {
						if($model->user_id != 0)											// email sudah menjadi member
							$this->addError('email', Yii::t('phrase', 'Email sudah terdaftar sebagai user'));
							
						else {																// email belum menjadi member
							$invite = self::model()->with('queue')->find(array(
								'select' => 'invite_id, publish, queue_id, user_id, invites',
								'condition' => 't.publish = :publish AND t.user_id = :user AND queue.email = :email',
								'params' => array(
									':publish' => '1',
									':user' => !Yii::app()->user->isGuest ? Yii::app()->user->id : '0',
									':email' => strtolower($this->email),
								),
							));
							if($invite == null)											// email sudah invite sebelumnya
								$this->addError('email', Yii::t('phrase', 'Email sudah di invite sebelumnya'));
						}
					}
				}
			}
			
			$this->code = self::getUniqueCode();
			$this->invite_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();
		if($this->isNewRecord && $this->queue->user_id == 0) {
			$setting = OmmuSettings::model()->findByPk(1, array(
				'select' => 'signup_checkemail',
			));
			if($setting->signup_checkemail == 1)
				SupportMailSetting::sendEmail($this->queue->email, $this->queue->email, 'User Invite', 'Silahkan bergabung dan masukkan code invite');
			
			else
				SupportMailSetting::sendEmail($this->queue->email, $this->queue->email, 'User Invite', 'Silahkan bergabung');
		}
	}

}