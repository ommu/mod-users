<?php
/**
 * UserNewsletterHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 October 2017, 05:38 WIB
 * @modified date 13 November 2018, 23:40 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_newsletter_history".
 *
 * The followings are the available columns in table "ommu_user_newsletter_history":
 * @property integer $id
 * @property integer $status
 * @property integer $newsletter_id
 * @property string $updated_date
 * @property string $updated_ip
 *
 * The followings are the available model relations:
 * @property UserNewsletter $newsletter
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class UserNewsletterHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['updated_ip'];

	public $email_search;
	public $userLevel;
	public $userDisplayname;
	public $register_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_newsletter_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['status', 'newsletter_id'], 'required'],
			[['status', 'newsletter_id'], 'integer'],
			[['updated_ip'], 'string', 'max' => 20],
			[['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNewsletter::className(), 'targetAttribute' => ['newsletter_id' => 'newsletter_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'status' => Yii::t('app', 'Status'),
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated IP'),
			'email_search' => Yii::t('app', 'Email'),
			'userLevel' => Yii::t('app', 'Level'),
			'userDisplayname' => Yii::t('app', 'User'),
			'register_search' => Yii::t('app', 'Registered'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNewsletter()
	{
		return $this->hasOne(UserNewsletter::className(), ['newsletter_id' => 'newsletter_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserNewsletterHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserNewsletterHistory(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['email_search'] = [
			'attribute' => 'email_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->newsletter) ? Yii::$app->formatter->asEmail($model->newsletter->email) : '-';
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('newsletter') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->newsletter->user) ? $model->newsletter->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('newsletter') ? true : false,
		];
		$this->templateColumns['userLevel'] = [
			'attribute' => 'userLevel',
			'value' => function($model, $key, $index, $column) {
				return isset($model->newsletter->user->level) ? $model->newsletter->user->level->title->message : '-';
			},
			'filter' => UserLevel::getLevel(),
			'visible' => !Yii::$app->request->get('newsletter') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['updated_ip'] = [
			'attribute' => 'updated_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->updated_ip;
			},
		];
		$this->templateColumns['register_search'] = [
			'attribute' => 'register_search',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->newsletter->view->register);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return $model->status == 1 ? Yii::t('app', 'Subscribe') : Yii::t('app', 'Unsubscribe');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
