<?php
/**
 * UserInviteHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 5 August 2017, 17:41 WIB
 * @modified date 24 July 2018, 05:33 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_invite_history".
 *
 * The followings are the available columns in table 'ommu_user_invite_history':
 * @property integer $id
 * @property integer $invite_id
 * @property string $code
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $expired_date
 *
 * The followings are the available model relations:
 * @property ViewUserInviteHistory $view
 * @property UserInvites $invite
 */

class UserInviteHistory extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array('code','invite_ip','expired_date');

	// Variable Search
	public $displayname_search;
	public $email_search;
	public $user_search;
	public $userlevel_search;
	public $expired_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserInviteHistory the static model class
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
		return $matches[1].'.ommu_user_invite_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invite_id, code, invite_date, invite_ip', 'required'),
			array('invite_id', 'numerical', 'integerOnly'=>true),
			array('invite_id', 'length', 'max'=>11),
			array('code', 'length', 'max'=>16),
			array('invite_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invite_id, code, invite_date, invite_ip, expired_date,
				displayname_search, email_search, user_search, userlevel_search, expired_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewUserInviteHistory', 'id'),
			'invite' => array(self::BELONGS_TO, 'UserInvites', 'invite_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'invite_id' => Yii::t('attribute', 'Invite'),
			'code' => Yii::t('attribute', 'Code'),
			'invite_date' => Yii::t('attribute', 'Invite Date'),
			'invite_ip' => Yii::t('attribute', 'Invite Ip'),
			'expired_date' => Yii::t('attribute', 'Expired Date'),
			'displayname_search' => Yii::t('attribute', 'Displayname'),
			'email_search' => Yii::t('attribute', 'Email'),
			'user_search' => Yii::t('attribute', 'Inviter'),
			'userlevel_search' => Yii::t('attribute', 'Inviter Level'),
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
			'invite' => array(
				'alias' => 'invite',
				'select' => 'newsletter_id, displayname',
			),
			'invite.newsletter' => array(
				'alias' => 'newsletter',
				'select' => 'email',
			),
			'invite.user' => array(
				'alias' => 'inviter',
				'select' => 'level_id, displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.invite_id', Yii::app()->getRequest()->getParam('invite') ? Yii::app()->getRequest()->getParam('invite') : $this->invite_id);
		$criteria->compare('t.code', strtolower($this->code), true);
		if($this->invite_date != null && !in_array($this->invite_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.invite_date)', date('Y-m-d', strtotime($this->invite_date)));
		$criteria->compare('t.invite_ip', strtolower($this->invite_ip), true);
		if($this->expired_date != null && !in_array($this->expired_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.expired_date)', date('Y-m-d', strtotime($this->expired_date)));

		$criteria->compare('invite.displayname', strtolower($this->displayname_search), true);
		$criteria->compare('newsletter.email', strtolower($this->email_search), true);
		$criteria->compare('inviter.displayname', strtolower($this->user_search), true);
		$criteria->compare('inviter.level_id', $this->userlevel_search);
		$criteria->compare('view.expired', $this->expired_search);

		if(!Yii::app()->getRequest()->getParam('UserInviteHistory_sort'))
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
			if(!Yii::app()->getRequest()->getParam('invite')) {
				$this->templateColumns['displayname_search'] = array(
					'name' => 'displayname_search',
					'value' => '$data->invite->displayname ? $data->invite->displayname : \'-\'',
				);
				$this->templateColumns['email_search'] = array(
					'name' => 'email_search',
					'value' => '$data->invite->newsletter->email ? $data->invite->newsletter->email : \'-\'',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->invite->user->displayname ? $data->invite->user->displayname : \'-\'',
				);
				$this->templateColumns['userlevel_search'] = array(
					'name' => 'userlevel_search',
					'value' => '$data->invite->user->level->title->message ? $data->invite->user->level->title->message : \'-\'',
					'filter' => UserLevel::getLevel(),
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
			$this->templateColumns['expired_date'] = array(
				'name' => 'expired_date',
				'value' => '!in_array($data->expired_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->expired_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'expired_date'),
			);
			$this->templateColumns['expired_search'] = array(
				'name' => 'expired_search',
				'value' => '$data->expired_search == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			$this->invite_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}