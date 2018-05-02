<?php
/**
 * UserNewsletterUser
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 2 May 2018, 13:19 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "_user_newsletter_user".
 *
 * The followings are the available columns in table "_user_newsletter_user":
 * @property integer $newsletter_id
 * @property string $user_id
 * @property string $register_date
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace app\modules\user\models\view;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\user\models\Users;

class UserNewsletterUser extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $user_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_newsletter_user';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['newsletter_id'];
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
			[['newsletter_id'], 'integer'],
			[['user_id'], 'number'],
			[['register_date'], 'string', 'max' => 19],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'newsletter_id' => Yii::t('app', 'Newsletter'),
			'user_id' => Yii::t('app', 'User'),
			'register_date' => Yii::t('app', 'Register Date'),
			'user_search' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
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
		$this->templateColumns['newsletter_id'] = [
			'attribute' => 'newsletter_id',
			'value' => function($model, $key, $index, $column) {
				return $model->newsletter_id;
			},
		];
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['register_date'] = [
			'attribute' => 'register_date',
			'value' => function($model, $key, $index, $column) {
				return $model->register_date;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['newsletter_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
