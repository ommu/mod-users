<?php
/**
 * UserNewsletterHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (www.ommu.co)
 * @modified date 24 July 2018, 05:33 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_newsletter_history".
 *
 * The followings are the available columns in table 'ommu_user_newsletter_history':
 * @property integer $id
 * @property integer $status
 * @property integer $newsletter_id
 * @property string $updated_date
 * @property string $updated_ip
 *
 * The followings are the available model relations:
 * @property UserNewsletter $newsletter
 */

class UserNewsletterHistory extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $level_search;
	public $user_search;
	public $email_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserNewsletterHistory the static model class
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
		return $matches[1].'.ommu_user_newsletter_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, newsletter_id, updated_ip', 'required'),
			array('status, newsletter_id', 'numerical', 'integerOnly'=>true),
			array('newsletter_id', 'length', 'max'=>11),
			array('updated_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status, newsletter_id, updated_date, updated_ip,
				level_search, user_search, email_search', 'safe', 'on'=>'search'),
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
			'newsletter' => array(self::BELONGS_TO, 'UserNewsletter', 'newsletter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'status' => Yii::t('attribute', 'Subscribe'),
			'newsletter_id' => Yii::t('attribute', 'Newsletter'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'updated_ip' => Yii::t('attribute', 'Updated IP'),
			'level_search' => Yii::t('attribute', 'Userlevel'),
			'user_search' => Yii::t('attribute', 'User'),
			'email_search' => Yii::t('attribute', 'Email'),
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
				'select' => 'user_id, email',
			),
			'newsletter.user' => array(
				'alias' => 'newsletterUser',
				'select' => 'level_id, email, displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.status', Yii::app()->getRequest()->getParam('status') ? Yii::app()->getRequest()->getParam('status') : $this->status);
		$criteria->compare('t.newsletter_id', Yii::app()->getRequest()->getParam('newsletter') ? Yii::app()->getRequest()->getParam('newsletter') : $this->newsletter_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));
		$criteria->compare('t.updated_ip', strtolower($this->updated_ip), true);

		$criteria->compare('newsletterUser.level_id', $this->level_search);
		$criteria->compare('newsletterUser.displayname', strtolower($this->user_search), true);
		$criteria->compare('newsletter.email', strtolower($this->email_search), true);

		if(!Yii::app()->getRequest()->getParam('UserNewsletterHistory_sort'))
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
			if(!Yii::app()->getRequest()->getParam('newsletter')) {
				$this->templateColumns['level_search'] = array(
					'name' => 'level_search',
					'value' => '$data->newsletter->user->level->title->message ? $data->newsletter->user->level->title->message : \'-\'',
					'filter' => UserLevel::getUserLevel(),
					'type' => 'raw',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->newsletter->user->displayname ? $data->newsletter->user->displayname : \'-\'',
				);
				$this->templateColumns['email_search'] = array(
					'name' => 'email_search',
					'value' => '$data->newsletter->email ? $data->newsletter->email : \'-\'',
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
			$this->templateColumns['status'] = array(
				'name' => 'status',
				//'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'status\', array(\'id\'=>$data->id)), $data->status, \'Subscribe,Unsubscribe\')',
				'value' => '$data->status == 1 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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
			$this->updated_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}