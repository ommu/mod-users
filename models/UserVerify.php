<?php
/**
 * UserVerify
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 05:31 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_verify".
 *
 * The followings are the available columns in table 'ommu_user_verify':
 * @property integer $verify_id
 * @property integer $publish
 * @property integer $user_id
 * @property string $code
 * @property string $verify_date
 * @property string $verify_ip
 * @property string $expired_date
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $deleted_date
 *
 * The followings are the available model relations:
 * @property ViewUserVerify $view
 * @property Users $user
 * @property Users $modified
 */

class UserVerify extends OActiveRecord
{
	use GridViewTrait;
	use UtilityTrait;

	public $gridForbiddenColumn = array('code','verify_ip','expired_date','modified_date','modified_search','deleted_date');
	public $email_i;

	// Variable Search
	public $level_search;
	public $user_search;
	public $email_search;
	public $modified_search;
	public $expired_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserVerify the static model class
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
		return $matches[1].'.ommu_user_verify';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, verify_ip, email_i', 'required'),
			array('publish, user_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('email_i', 'email'),
			array('publish, user_id', 'safe'),
			array('user_id', 'length', 'max'=>11),
			array('verify_ip', 'length', 'max'=>20),
			array('email_i', 'length', 'max'=>32),
			array('code', 'length', 'max'=>64),
			// array('verify_date, modified_date, deleted_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('verify_id, publish, user_id, code, verify_date, verify_ip, expired_date, modified_date, modified_id, deleted_date,
				level_search, user_search, email_search, modified_search, expired_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewUserVerify', 'verify_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'verify_id' => Yii::t('attribute', 'Verify'),
			'publish' => Yii::t('attribute', 'Publish'),
			'user_id' => Yii::t('attribute', 'User'),
			'code' => Yii::t('attribute', 'Code'),
			'verify_date' => Yii::t('attribute', 'Verify Date'),
			'verify_ip' => Yii::t('attribute', 'Verify Ip'),
			'expired_date' => Yii::t('attribute', 'Expired Date'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'deleted_date' => Yii::t('attribute', 'Deleted Date'),
			'email_i' => Yii::t('attribute', 'Email'),
			'level_search' => Yii::t('attribute', 'level'),
			'user_search' => Yii::t('attribute', 'User'),
			'email_search' => Yii::t('attribute', 'Email'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'expired_search' => Yii::t('attribute', 'Expired'),
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
			'user' => array(
				'alias' => 'user',
				'select' => 'level_id, email, displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.verify_id', $this->verify_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.code', strtolower($this->code), true);
		if($this->verify_date != null && !in_array($this->verify_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.verify_date)', date('Y-m-d', strtotime($this->verify_date)));
		$criteria->compare('t.verify_ip', strtolower($this->verify_ip), true);
		if($this->expired_date != null && !in_array($this->expired_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.expired_date)', date('Y-m-d', strtotime($this->expired_date)));
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->deleted_date != null && !in_array($this->deleted_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.deleted_date)', date('Y-m-d', strtotime($this->deleted_date)));

		$criteria->compare('user.level_id', $this->level_search);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);
		$criteria->compare('user.email', strtolower($this->email_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.publish', $this->expired_search);

		if(!Yii::app()->getRequest()->getParam('UserVerify_sort'))
			$criteria->order = 't.verify_id DESC';

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
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['level_search'] = array(
					'name' => 'level_search',
					'value' => '$data->user->level->title->message ? $data->user->level->title->message : \'-\'',
					'filter' => UserLevel::getLevel(),
					'type' => 'raw',
				);
				$this->templateColumns['email_search'] = array(
					'name' => 'email_search',
					'value' => '$data->user->email ? $data->user->email : \'-\'',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['code'] = array(
				'name' => 'code',
				'value' => '$data->code',
			);
			$this->templateColumns['verify_date'] = array(
				'name' => 'verify_date',
				'value' => '!in_array($data->verify_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->verify_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'verify_date'),
			);
			$this->templateColumns['verify_ip'] = array(
				'name' => 'verify_ip',
				'value' => '$data->verify_ip',
			);
			$this->templateColumns['expired_date'] = array(
				'name' => 'expired_date',
				'value' => '!in_array($data->expired_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->expired_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'expired_date'),
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
			$this->templateColumns['deleted_date'] = array(
				'name' => 'deleted_date',
				'value' => '!in_array($data->deleted_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->deleted_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'deleted_date'),
			);
			$this->templateColumns['expired_search'] = array(
				'name' => 'expired_search',
				'value' => '$data->view->expired == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->verify_id)), $data->publish)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
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
			if($this->isNewRecord) {
				if(preg_match('/@/', $this->email_i)) {
					$user = Users::model()->findByAttributes(array('email' => strtolower($this->email_i)), array(
						'select' => 'user_id, verified',
					));
				} else {
					$user = Users::model()->findByAttributes(array('username' => strtolower($this->email_i)), array(
						'select' => 'user_id, verified',
					));
				}
				if($user === null)
					$this->addError('email_i', Yii::t('phrase', 'Incorrect email address/username'));
				else {
					if($user->verified == 1)
						$this->addError('email_i', Yii::t('phrase', 'Your account verified'));
					else
						$this->user_id = $user->user_id;
				}
				
				$this->code = $this->uniqueCode();
				$this->verify_ip = $_SERVER['REMOTE_ADDR'];
				
			} else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		Yii::import('ext.phpmailer.Mailer');
		
		$setting = OmmuSettings::model()->findByPk(1, array(
			'select' => 'site_title',
		));

		$assets = Yii::getPathOfAlias('users.assets');
		if(!file_exists($assets))
			$assets = Yii::getPathOfAlias('ommu.users.assets');
		$_assetsUrl = Yii::app()->assetManager->publish($assets);
		
		parent::afterSave();

		if($this->isNewRecord) {
			// Update all history
			$criteria=new CDbCriteria;
			$criteria->addNotInCondition('verify_id', array($this->verify_id));
			$criteria->compare('publish', 1);
			$criteria->compare('user_id', $this->user_id);

			self::model()->updateAll(array('publish'=>0), $criteria);
			
			// Send Email to Member
			$verify_search = array(
				'{displayname}', '{site_title}',
				'{verify_link}',
			);
			$verify_link = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->createUrl('account/email', array('token'=>$this->code));
			$verify_replace = array(
				$this->user->displayname, $setting->site_title,
				CHtml::link($verify_link, $verify_link),
			);
			$verify_template = 'user_verify_email';
			$verify_title = Yii::t('phrase', 'Please verify your {site_title} account', array('{site_title}'=>$setting->site_title));
			$verify_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$verify_template.'.php';
			if(!file_exists($verify_file))
				$verify_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$verify_template.'.php';
			$verify_message = Utility::getEmailTemplate(str_ireplace($verify_search, $verify_replace, file_get_contents($verify_file)));
			//Mailer::send($this->user->email, $this->user->displayname, $verify_title, $verify_message);
		}
	}

}