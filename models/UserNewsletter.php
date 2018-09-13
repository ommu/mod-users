<?php
/**
 * UserNewsletter
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 05:32 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_newsletter".
 *
 * The followings are the available columns in table 'ommu_user_newsletter':
 * @property integer $newsletter_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $reference_id
 * @property string $email
 * @property string $subscribe_date
 * @property integer $subscribe_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $updated_ip
 *
 * The followings are the available model relations:
 * @property UserInvites[] $invites
 * @property UserInvites[] $inviteAll
 * @property ViewUserNewsletter $view
 * @property Users $reference
 * @property Users $user
 * @property UserNewsletterHistory[] $histories
 * @property Users $modified
 */

class UserNewsletter extends OActiveRecord
{
	use GridViewTrait;
	use FileTrait;

	public $gridForbiddenColumn = array('subscribe_date','subscribe_search','modified_date','modified_search','updated_date','updated_ip');
	public $email_i;

	// Variable Search
	public $reference_search;
	public $subscribe_search;
	public $level_search;
	public $user_search;
	public $modified_search;
	public $register_search;
	public $invite_search;
	public $invite_user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserNewsletter the static model class
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
		return $matches[1].'.ommu_user_newsletter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email_i', 'required'),
			array('status, user_id, reference_id, subscribe_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('email', 'email'),
			array('status, user_id, reference_id, subscribe_id', 'safe'),
			array('user_id, reference_id, subscribe_id, modified_id', 'length', 'max'=>11),
			array('updated_ip', 'length', 'max'=>20),
			array('email', 'length', 'max'=>32),
			// array('subscribe_date, modified_date, updated_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('newsletter_id, status, user_id, reference_id, email, subscribe_date, subscribe_id, modified_date, modified_id, updated_date, updated_ip,
				reference_search, subscribe_search, level_search, user_search, modified_search, register_search, invite_search, invite_user_search', 'safe', 'on'=>'search'),
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
			'invites' => array(self::HAS_MANY, 'UserInvites', 'newsletter_id', 'on'=>'invites.publish=1'),
			'inviteAll' => array(self::HAS_MANY, 'UserInvites', 'newsletter_id'),
			'view' => array(self::BELONGS_TO, 'ViewUserNewsletter', 'newsletter_id'),
			'reference' => array(self::BELONGS_TO, 'Users', 'reference_id'),
			'subscribe' => array(self::BELONGS_TO, 'Users', 'subscribe_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'histories' => array(self::HAS_MANY, 'UserNewsletterHistory', 'newsletter_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'newsletter_id' => Yii::t('attribute', 'Newsletter'),
			'status' => Yii::t('attribute', 'Subscribe'),
			'user_id' => Yii::t('attribute', 'User'),
			'reference_id' => Yii::t('attribute', 'Reference'),
			'email' => Yii::t('attribute', 'Email'),
			'subscribe_date' => Yii::t('attribute', 'Subscribe Date'),
			'subscribe_id' => Yii::t('attribute', 'Subscribe'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'updated_ip' => Yii::t('attribute', 'Updated IP'),
			'email_i' => Yii::t('attribute', 'Email'),
			'reference_search' => Yii::t('attribute', 'Reference'),
			'subscribe_search' => Yii::t('attribute', 'Subscribe'),
			'level_search' => Yii::t('attribute', 'Level'),
			'user_search' => Yii::t('attribute', 'User'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'register_search' => Yii::t('attribute', 'Register'),
			'invite_search' => Yii::t('attribute', 'Invites'),
			'invite_user_search' => Yii::t('attribute', 'Invite Users'),
			'first_invite_i' => Yii::t('attribute', 'First Invite'),
			'last_invite_i' => Yii::t('attribute', 'Last Invite'),
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
				'select' => 'level_id, displayname',
			),
			'reference' => array(
				'alias' => 'reference',
				'select' => 'displayname',
			),
			'subscribe' => array(
				'alias' => 'subscribe',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.newsletter_id', $this->newsletter_id);
		$criteria->compare('t.status', Yii::app()->getRequest()->getParam('status') ? Yii::app()->getRequest()->getParam('status') : $this->status);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.reference_id', Yii::app()->getRequest()->getParam('reference') ? Yii::app()->getRequest()->getParam('reference') : $this->reference_id);
		$criteria->compare('t.email', strtolower($this->email), true);
		if($this->subscribe_date != null && !in_array($this->subscribe_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.subscribe_date)', date('Y-m-d', strtotime($this->subscribe_date)));
		$criteria->compare('t.status', Yii::app()->getRequest()->getParam('subscribe') ? Yii::app()->getRequest()->getParam('subscribe') : $this->subscribe_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));
		$criteria->compare('t.updated_ip', strtolower($this->updated_ip), true);

		$criteria->compare('user.level_id', $this->level_search);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);			//user.displayname
		$criteria->compare('reference.displayname', strtolower($this->reference_search), true);			//reference.displayname
		$criteria->compare('subscribe.displayname', strtolower($this->subscribe_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.register', $this->register_search);
		$criteria->compare('view.invites', $this->invite_search);
		$criteria->compare('view.invite_users', $this->invite_user_search);
		
		if(!Yii::app()->getRequest()->getParam('UserNewsletter_sort'))
			$criteria->order = 't.newsletter_id DESC';

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
					'value' => '$data->user_id ? $data->user->level->title->message : \'-\'',
					'filter' => UserLevel::getLevel(),
					'type' => 'raw',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['email'] = array(
				'name' => 'email',
				'value' => '$data->email',
			);
			if(!Yii::app()->getRequest()->getParam('reference')) {
				$this->templateColumns['reference_search'] = array(
					'name' => 'reference_search',
					'value' => '$data->reference->displayname ? $data->reference->displayname : \'-\'',
				);
			}
			$this->templateColumns['subscribe_date'] = array(
				'name' => 'subscribe_date',
				'value' => '!in_array($data->subscribe_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->subscribe_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'subscribe_date'),
			);
			if(!Yii::app()->getRequest()->getParam('subscribe')) {
				$this->templateColumns['subscribe_search'] = array(
					'name' => 'subscribe_search',
					'value' => '$data->subscribe->displayname ? $data->subscribe->displayname : \'-\'',
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
			$this->templateColumns['updated_date'] = array(
				'name' => 'updated_date',
				'value' => '!in_array($data->updated_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->updated_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'updated_date'),
			);
			$this->templateColumns['updated_ip'] = array(
				'name' => 'updated_ip',
				'value' => '$data->updated_ip',
			);
			$this->templateColumns['invite_search'] = array(
				'name' => 'invite_search',
				'value' => 'CHtml::link($data->view->invites ? $data->view->invites : 0, Yii::app()->controller->createUrl(\'o/invite/manage\', array("newsletter"=>$data->newsletter_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['invite_user_search'] = array(
				'name' => 'invite_user_search',
				'value' => '$data->view->invite_users ? $data->view->invite_users : 0',
				//'value' => 'CHtml::link($data->view->invite_users ? $data->view->invite_users : 0, Yii::app()->controller->createUrl("o/invite/manage", array("newsletter"=>$data->newsletter_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['register_search'] = array(
				'name' => 'register_search',
				'value' => '$data->view->register == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['status'] = array(
				'name' => 'status',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'status\', array(\'id\'=>$data->newsletter_id)), $data->status, \'Subscribe,Unsubscribe\')',
				//'value' => '$data->status == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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

	/**
	 * User get information
	 *
	 * condition
	 ** 0 = newsletter not null
	 ** 1 = newsletter save
	 ** 2 = newsletter not save
	 */
	public static function insertNewsletter($email)
	{
		$email = strtolower($email);
		$criteria=new CDbCriteria;
		$criteria->select = 'newsletter_id';
		$criteria->compare('email', $email);
		$model = self::model()->find($criteria);
		
		$condition = 0;
		if($model == null) {
			$newsletter = new UserNewsletter;
			$newsletter->email_i = $email;
			if($newsletter->save())
				$condition = 1;
			else
				$condition = 2;
		}
		
		return $condition;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->email_i = strtolower($this->email_i);
				if($this->email_i != '') {
					$email_i = $this->formatFileType($this->email_i);
					if(count($email_i) == 1) {
						$this->email = $this->email_i;
						$newsletter = self::model()->findByAttributes(array('email' => strtolower($this->email)), array(
							'select' => 'newsletter_id, email',
						));
						if($newsletter != null)
							$this->addError('email_i', Yii::t('phrase', 'Email {email} sudah terdaftar pada newsletter.', array('{email}'=>$this->email)));
					}
				}
				if($this->subscribe_id == null)
					$this->subscribe_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			} else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			$this->updated_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		if(parent::beforeSave()) {
			$this->email = strtolower($this->email);
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		Yii::import('ext.phpmailer.Mailer');
		
		parent::afterSave();

		if($this->isNewRecord) {
			// Guest Subscribe
			if($this->status == 1 && $this->user_id == null && $this->subscribe_id == null) {
				$displayname = $this->user->displayname ? $this->user->displayname : $this->email;

				$subscribe_search = array(
					 '{displayname}',
				);
				$subscribe_replace = array(
					$displayname,
				);

				$subscribe_template = 'user_newsletter_subscribe';
				$subscribe_title = Yii::t('phrase', 'Subscribe Success');
				$subscribe_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$subscribe_template.'.php';
				if(!file_exists($subscribe_file))
					$subscribe_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$subscribe_template.'.php';
				$subscribe_message = Utility::getEmailTemplate(str_ireplace($subscribe_search, $subscribe_replace, file_get_contents($subscribe_file)));
				//Mailer::send($this->email, $displayname, $subscribe_title, $subscribe_message);
			}
			
		} else {
			// Guest Unsubscribe
			$email = $this->email;
			$displayname = $this->email;
			// Member Unsubscribe
			if(!$this->user_id) {
				$email = $this->user->email;
				$displayname = $this->user->displayname;
			}

			$unsubscribe_search = array(
				 '{displayname}',
			);
			$unsubscribe_replace = array(
				$displayname,
			);
			
			if($this->status == 0) {
				$unsubscribe_template = 'user_newsletter_unsubscribe';
				$unsubscribe_title = Yii::t('phrase', 'Unsubscribe Success');
				$unsubscribe_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$unsubscribe_template.'.php';
				if(!file_exists($unsubscribe_file))
					$unsubscribe_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$unsubscribe_template.'.php';
				$unsubscribe_message = Utility::getEmailTemplate(str_ireplace($unsubscribe_search, $unsubscribe_replace, file_get_contents($unsubscribe_file)));
				//Mailer::send($email, $displayname, $unsubscribe_title, $unsubscribe_message);
			} else {
				$unsubscribe_template = 'user_newsletter_unsubscribe';
				$unsubscribe_title = Yii::t('phrase', 'Unsubscribe Success');
				$unsubscribe_file = YiiBase::getPathOfAlias('users.components.templates').'/'.$unsubscribe_template.'.php';
				if(!file_exists($unsubscribe_file))
					$unsubscribe_file = YiiBase::getPathOfAlias('ommu.users.components.templates').'/'.$unsubscribe_template.'.php';
				$unsubscribe_message = Utility::getEmailTemplate(str_ireplace($unsubscribe_search, $unsubscribe_replace, file_get_contents($unsubscribe_file)));
				//Mailer::send($email, $displayname, $unsubscribe_title, $unsubscribe_message);
			}
		}
	}

}