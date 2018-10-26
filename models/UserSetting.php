<?php
/**
 * UserSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 9 October 2017, 11:21 WIB
 * @modified date 2 May 2018, 13:34 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_setting".
 *
 * The followings are the available columns in table "ommu_user_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $forgot_diff_type
 * @property integer $forgot_difference
 * @property string $verify_diff_type
 * @property integer $verify_difference
 * @property string $invite_diff_type
 * @property integer $invite_difference
 * @property string $invite_order
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\view\UserSetting as UserSettingView;

class UserSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Variable Search
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_setting';
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
			[['license', 'permission', 'meta_keyword', 'meta_description', 'forgot_diff_type', 'forgot_difference', 'verify_diff_type', 'verify_difference', 'invite_diff_type', 'invite_difference', 'invite_order'], 'required'],
			[['permission', 'forgot_difference', 'verify_difference', 'invite_difference', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description', 'forgot_diff_type', 'verify_diff_type', 'invite_diff_type', 'invite_order'], 'string'],
			[['modified_date'], 'safe'],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'forgot_diff_type' => Yii::t('app', 'Forgot Diff Type'),
			'forgot_difference' => Yii::t('app', 'Forgot Difference'),
			'verify_diff_type' => Yii::t('app', 'Verify Diff Type'),
			'verify_difference' => Yii::t('app', 'Verify Difference'),
			'invite_diff_type' => Yii::t('app', 'Invite Diff Type'),
			'invite_difference' => Yii::t('app', 'Invite Difference'),
			'invite_order' => Yii::t('app', 'Invite Order'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(UserSettingView::className(), ['id' => 'id']);
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
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['forgot_diff_type'] = [
			'attribute' => 'forgot_diff_type',
			'value' => function($model, $key, $index, $column) {
				return $model->forgot_diff_type;
			},
		];
		$this->templateColumns['forgot_difference'] = [
			'attribute' => 'forgot_difference',
			'value' => function($model, $key, $index, $column) {
				return $model->forgot_difference;
			},
		];
		$this->templateColumns['verify_diff_type'] = [
			'attribute' => 'verify_diff_type',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_diff_type;
			},
		];
		$this->templateColumns['verify_difference'] = [
			'attribute' => 'verify_difference',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_difference;
			},
		];
		$this->templateColumns['invite_diff_type'] = [
			'attribute' => 'invite_diff_type',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_diff_type;
			},
		];
		$this->templateColumns['invite_difference'] = [
			'attribute' => 'invite_difference',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_difference;
			},
		];
		$this->templateColumns['invite_order'] = [
			'attribute' => 'invite_order',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_order;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter' => Html::input('date', 'modified_date', Yii::$app->request->get('modified_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->permission);
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => 1])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * get Module License
	 */
	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}

}
