<?php
/**
 * UserNewsletterHistory
 * version: 0.0.1
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

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 05:38 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models;

use Yii;
use yii\helpers\Url;
use app\libraries\grid\GridView;

class UserNewsletterHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $level_search;
	public $user_search;
	public $email_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_newsletter_history';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['status', 'newsletter_id', 'updated_ip'], 'required'],
			[['status', 'newsletter_id'], 'integer'],
			[['updated_date'], 'safe'],
			[['updated_ip'], 'string', 'max' => 20],
			[['newsletter_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNewsletter::className(), 'targetAttribute' => ['newsletter_id' => 'newsletter_id']],
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'status' => Yii::t('app', 'Subscribe'),
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated Ip'),
			'level_search' => Yii::t('app', 'Level'),
			'user_search' => Yii::t('app', 'User'),
			'email_search' => Yii::t('app', 'Email'),
		];
	}
	
	/**
	 * Set default columns to display
	 */
	public function init() 
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!isset($_GET['newsletter'])) {
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return $model->newsletter->user_id ? $model->newsletter->user->level->title->message : '-';
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return $model->newsletter->user_id ? $model->newsletter->user->displayname : '-';
				},
			];
			$this->templateColumns['email_search'] = [
				'attribute' => 'email_search',
				'value' => function($model, $key, $index, $column) {
					return $model->newsletter->email;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['updated_ip'] = 'updated_ip';
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter' => GridView::getFilterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->status == 1 ? Yii::t('app', 'Subscribe') : Yii::t('app', 'Unsubscribe');
			},
			'contentOptions' => ['class'=>'center'],
		];
	}
}
