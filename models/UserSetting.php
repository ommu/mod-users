<?php
/**
 * UserSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 9 October 2017, 11:21 WIB
 * @modified date 8 November 2018, 12:39 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_setting".
 *
 * The followings are the available columns in table "ommu_user_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
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
	public $gridForbiddenColumn = [];

	// Search Variable
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_description', 'meta_keyword', 'forgot_difference', 'verify_difference', 'invite_difference', 'invite_order'], 'required'],
			[['permission', 'forgot_difference', 'verify_difference', 'invite_difference', 'modified_id'], 'integer'],
			[['meta_description', 'meta_keyword', 'forgot_diff_type', 'verify_diff_type', 'invite_diff_type', 'invite_order'], 'string'],
			[['forgot_diff_type','verify_diff_type', 'invite_diff_type'], 'safe'],
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
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
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
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserSetting the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserSetting(get_called_class());
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
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['forgot_diff_type'] = [
			'attribute' => 'forgot_diff_type',
			'value' => function($model, $key, $index, $column) {
				return self::getForgotDiffType($model->forgot_diff_type);
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
				return self::getForgotDiffType($model->verify_diff_type);
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
				return self::getForgotDiffType($model->invite_diff_type);
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
				return self::getInviteOrder($model->invite_order);
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
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
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, the public can view "module name" unless they are made private.'),
			0 => Yii::t('app', 'No, the public cannot view "module name".'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getForgotDiffType
	 */
	public static function getForgotDiffType($value=null)
	{
		$items = array(
			'0' => Yii::t('app', 'Day'),
			'1' => Yii::t('app', 'Hour'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getInviteOrder
	 */
	public static function getInviteOrder($value=null)
	{
		$items = array(
			'asc' => Yii::t('app', 'Ascending'),
			'desc' => Yii::t('app', 'Descending'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->forgot_difference != '') {
				if($this->forgot_diff_type == '')
					$this->addError('forgot_difference', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('forgot_diff_type')]));
			}
			if($this->verify_difference != '') {
				if($this->verify_diff_type == '')
					$this->addError('verify_difference', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('verify_diff_type')]));
			}
			if($this->invite_difference != '') {
				if($this->invite_diff_type == '')
					$this->addError('invite_difference', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('invite_diff_type')]));
			}
			
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
		}
		return true;
	}
}
