<?php
/**
 * UserInvites
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 05:32 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_invites".
 *
 * The followings are the available columns in table 'ommu_user_invites':
 * @property integer $invite_id
 * @property integer $publish
 * @property integer $newsletter_id
 * @property integer $user_id
 * @property string $displayname
 * @property string $code
 * @property integer $invites
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property UserInviteHistory[] $histories
 * @property UserNewsletter $newsletter
 * @property Users $user
 * @property Users $modified
 */

class UserInvites extends OActiveRecord
{
	use GridViewTrait;
	use UtilityTrait;

	public $gridForbiddenColumn = array('code','invite_date','invite_ip','modified_date','modified_search','updated_date');
	public $email_i;

	// Variable Search
	public $email_search;
	public $userlevel_search;
	public $user_search;
	public $modified_search;
	public $register_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
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
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.ommu_user_invites';
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
			array('publish, newsletter_id, user_id, invites, modified_id', 'numerical', 'integerOnly'=>true),
			array('publish, user_id, displayname, code, invites, invite_ip', 'safe'),
			array('newsletter_id, user_id, modified_id', 'length', 'max'=>11),
			array('code', 'length', 'max'=>16),
			array('invite_ip', 'length', 'max'=>20),
			array('displayname', 'length', 'max'=>64),
			// array('invite_date, modified_date, updated_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('invite_id, publish, newsletter_id, user_id, displayname, code, invites, invite_date, invite_ip, modified_date, modified_id, updated_date,
				newsletter_search, user_search, modified_search', 'safe', 'on'=>'search'),
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
			'histories' => array(self::HAS_MANY, 'UserInviteHistory', 'invite_id'),
			'newsletter' => array(self::BELONGS_TO, 'UserNewsletter', 'newsletter_id'),
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
			'invite_id' => Yii::t('attribute', 'Invite'),
			'publish' => Yii::t('attribute', 'Publish'),
			'newsletter_id' => Yii::t('attribute', 'Newsletter'),
			'user_id' => Yii::t('attribute', 'Inviter'),
			'displayname' => Yii::t('attribute', 'Displayname'),
			'code' => Yii::t('attribute', 'Code'),
			'invites' => Yii::t('attribute', 'Invites'),
			'invite_date' => Yii::t('attribute', 'Invite Date'),
			'invite_ip' => Yii::t('attribute', 'Invite Ip'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'email_i' => Yii::t('attribute', 'Email'),
			'email_search' => Yii::t('attribute', 'Email'),
			'userlevel_search' => Yii::t('attribute', 'Level Inviter'),
			'user_search' => Yii::t('attribute', 'Inviter'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'register_search' => Yii::t('attribute', 'Register'),
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
			'newsletter' => array(
				'alias' => 'newsletter',
				'select' => 'newsletter_id, user_id, email',
			),
			'newsletter.view' => array(
				'alias' => 'newsletterView',
				'select' => 'register',
			),
			'user' => array(
				'alias' => 'user',
				'select' => 'level_id, displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.invite_id', $this->invite_id);
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
		$criteria->compare('t.newsletter_id', Yii::app()->getRequest()->getParam('newsletter') ? Yii::app()->getRequest()->getParam('newsletter') : $this->newsletter_id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.displayname', strtolower($this->displayname), true);
		$criteria->compare('t.code', strtolower($this->code), true);
		$criteria->compare('t.invites', $this->invites);
		if($this->invite_date != null && !in_array($this->invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.invite_date)', date('Y-m-d', strtotime($this->invite_date)));
		$criteria->compare('t.invite_ip', strtolower($this->invite_ip), true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('newsletter.email', strtolower($this->email_search), true);
		$criteria->compare('user.level_id', $this->userlevel_search);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('newsletterView.register', $this->register_search);

		if(!Yii::app()->getRequest()->getParam('UserInvites_sort'))
			$criteria->order = 't.invite_id DESC';

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
			$this->templateColumns['displayname'] = array(
				'name' => 'displayname',
				'value' => '$data->displayname',
			);
			if(!Yii::app()->getRequest()->getParam('newsletter')) {
				$this->templateColumns['email_search'] = array(
					'name' => 'email_search',
					'value' => '$data->newsletter->email',
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
				$this->templateColumns['userlevel_search'] = array(
					'name' => 'userlevel_search',
					'value' => '$data->user_id ? $data->user->level->title->message : \'-\'',
					'filter' => UserLevel::getUserLevel(),
					'type' => 'raw',
				);
			}
			$this->templateColumns['code'] = array(
				'name' => 'code',
				'value' => '$data->code',
			);
			$this->templateColumns['invite_date'] = array(
				'name' => 'invite_date',
				'value' => '!in_array($data->invite_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->invite_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'invite_date'),
			);
			$this->templateColumns['invite_ip'] = array(
				'name' => 'invite_ip',
				'value' => '$data->invite_ip',
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
			$this->templateColumns['updated_date'] = array(
				'name' => 'updated_date',
				'value' => '!in_array($data->updated_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->updated_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'updated_date'),
			);
			$this->templateColumns['invites'] = array(
				'name' => 'invites',
				'value' => 'CHtml::link($data->invites ? $data->invites : 0, Yii::app()->controller->createUrl(\'history/invite/manage\', array("invite"=>$data->invite_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['register_search'] = array(
				'name' => 'register_search',
				'value' => '$data->newsletter->view->register == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->invite_id)), $data->publish)',
					//'value' => '$data->publish == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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
	 * getInvite
	 */
	public static function getInvite($email) 
	{
		$email = strtolower($email);

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'newsletter' => array(
				'alias' => 'newsletter',
				'select' => 'newsletter_id, user_id, email',
			),
		);
		$criteria->compare('t.publish', 1);
		$criteria->addCondition('t.user_id IS NOT NULL');
		$criteria->compare('newsletter.email', $email);
		$criteria->order ='t.invite_id DESC';
		$model = self::model()->find($criteria);
		
		return $model;
	}

	/**
	 * getInviteWithCode
	 */
	public static function getInviteWithCode($email, $code)
	{
		$email = strtolower($email);

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'view' => array(
				'alias' => 'view',
			),
			'invite' => array(
				'alias' => 'invite',
				'select' => 'publish, newsletter_id, user_id',
			),
			'invite.newsletter' => array(
				'alias' => 'newsletter',
				'select' => 'status, email'
			),
		);
		$criteria->compare('t.code', $code);
		$criteria->compare('view.expired', 0);
		$criteria->compare('invite.publish', 1);
		$criteria->addCondition('invite.user_id IS NOT NULL');
		$criteria->compare('newsletter.status', 1);
		$criteria->compare('newsletter.email', $email);
		$model = UserInviteHistory::model()->find($criteria);
		
		return $model;
	}

	/**
	 * insertInvite
	 * 
	 * condition
	 ** 0 = newsletter not null
	 ** 1 = newsletter save
	 ** 2 = newsletter not save
	 */
	public static function insertInvite($email, $user_id)
	{
		$email = strtolower($email);

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'newsletter' => array(
				'alias' => 'newsletter',
				'select' => 'newsletter_id, user_id, email',
			),
		);
		$criteria->compare('t.publish', 1);
		if($user_id != null)
			$criteria->compare('t.user_id', $user_id);
		else
			$criteria->addCondition('t.user_id IS NULL');
		$criteria->compare('newsletter.email', $email);
		$criteria->order = 't.invite_id DESC';
		
		$inviteFind = UserInvites::model()->find($criteria);
		
		$condition = 0;
		if($inviteFind == null) {
			$invite = new UserInvites;
			$invite->email_i = $email;
			$invite->user_id = $user_id;
			if($invite->save())
				$condition = 1;
			else
				$condition = 2;
				
		} else {
			if($inviteFind->newsletter->user_id == null) {
				$inviteFind->invites = $inviteFind->invites+1;
				if($inviteFind->update())
					$condition = 1;
				else
					$condition = 2;
			}
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
						$newsletter = UserNewsletter::model()->findByAttributes(array('email'=>strtolower($this->email_i)), array(
							'select' => 'newsletter_id, user_id',
						));
						if($newsletter != null && $newsletter->user_id != null)
							$this->addError('email_i', Yii::t('phrase', 'Email {email} sudah terdaftar sebagai user.', array('{email}'=>$this->email_i)));
					}
				}
				if($this->user_id == null)
					$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
				$this->code = $this->uniqueCode(8,2);

			} else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			$this->invite_ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return true;
	}

	/**
	 * before save attributes
	 */
	protected function beforeSave()
	{ 
		if(parent::beforeSave()) {
			$this->email_i = strtolower($this->email_i);
			if($this->isNewRecord) {
				$newsletter = UserNewsletter::model()->findByAttributes(array('email'=>strtolower($this->email_i)), array(
					'select' => 'newsletter_id',
				));

				if($newsletter != null)
					$this->newsletter_id = $newsletter->newsletter_id;
				else {
					$newsletter = new UserNewsletter;
					$newsletter->email_i = $this->email_i;
					if($newsletter->save())
						$this->newsletter_id = $newsletter->newsletter_id;
				}
			}
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
			'select' => 'signup_checkemail',
		));
		
		parent::afterSave();
		
		if($this->isNewRecord && $this->newsletter->user_id == null) {
			if($setting->signup_checkemail == 1)
				$signup_checkemail = 1;
				//Mailer::send($this->newsletter->email, $this->newsletter->email, 'User Invite', 'Silahkan bergabung dan masukkan code invite');
			
			else
				$signup_checkemail = 0;
				//Mailer::send($this->newsletter->email, $this->newsletter->email, 'User Invite', 'Silahkan bergabung');
		}
	}

}