<?php
/**
 * UserLevel
 * version: 0.0.1
 *
 * This is the model class for table "ommu_user_level".
 *
 * The followings are the available columns in table "ommu_user_level":
 * @property integer $level_id
 * @property integer $name
 * @property integer $desc
 * @property integer $default
 * @property integer $signup
 * @property integer $message_allow
 * @property string $message_limit
 * @property integer $profile_block
 * @property integer $profile_search
 * @property string $profile_privacy
 * @property string $profile_comments
 * @property integer $profile_style
 * @property integer $profile_style_sample
 * @property integer $profile_status
 * @property integer $profile_invisible
 * @property integer $profile_views
 * @property integer $profile_change
 * @property integer $profile_delete
 * @property integer $photo_allow
 * @property string $photo_size
 * @property string $photo_exts
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Users[] $users

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 07:43 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models;

use Yii;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use app\coremodules\user\models\Users;
use app\components\Utility;
use app\models\SourceMessage;
use app\libraries\grid\GridView;
use app\coremodules\user\models\view\UserLevel as UserLevelView;

class UserLevel extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['profile_block', 'profile_search', 'profile_privacy', 'profile_comments', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'photo_size', 'photo_exts', 'message_allow', 'message_limit', 'modified_date', 'modified_search', 'slug'];

	// Variable Search
	public $name_i;
	public $desc_i;
	public $creation_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_level';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class'		=> SluggableBehavior::className(),
				'attribute'	=> 'name',
				'immutable'	=> true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['default', 'name_i', 'desc_i'], 'required', 'on'=>'info'],
			[['profile_block', 'profile_search', 'profile_privacy', 'profile_comments', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'photo_size', 'photo_exts'], 'required', 'on'=>'user'],
			[['message_allow', 'message_limit'], 'required', 'on'=>'message'],
			[['name', 'desc', 'default', 'signup', 'message_allow', 'profile_block', 'profile_search', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'creation_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date', 'slug'], 'safe'],
			[['slug', 'name_i'], 'string', 'max' => 32],
			[['desc_i'], 'string', 'max' => 256],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(Users::className(), ['level_id' => 'level_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
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
		return $this->hasOne(UserLevelView::className(), ['level_id' => 'level_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'level_id' => Yii::t('app', 'Level'),
			'name' => Yii::t('app', 'Name'),
			'desc' => Yii::t('app', 'Description'),
			'default' => Yii::t('app', 'Default'),
			'signup' => Yii::t('app', 'Signup'),
			'message_allow' => Yii::t('app', 'Message Allow'),
			'message_limit' => Yii::t('app', 'Message Limit'),
			'profile_block' => Yii::t('app', 'Profile Block'),
			'profile_search' => Yii::t('app', 'Profile'),
			'profile_privacy' => Yii::t('app', 'Profile Privacy'),
			'profile_comments' => Yii::t('app', 'Profile Comments'),
			'profile_style' => Yii::t('app', 'Profile Style'),
			'profile_style_sample' => Yii::t('app', 'Profile Style Sample'),
			'profile_status' => Yii::t('app', 'Profile Status'),
			'profile_invisible' => Yii::t('app', 'Profile Invisible'),
			'profile_views' => Yii::t('app', 'Profile Views'),
			'profile_change' => Yii::t('app', 'Profile Change'),
			'profile_delete' => Yii::t('app', 'Profile Delete'),
			'photo_allow' => Yii::t('app', 'Photo Allow'),
			'photo_size' => Yii::t('app', 'Photo Size'),
			'photo_exts' => Yii::t('app', 'Photo Exts'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'slug' => Yii::t('app', 'Slug'),
			'name_i' => Yii::t('app', 'Name'),
			'desc_i' => Yii::t('app', 'Description'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
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
		$this->templateColumns['name_i'] = [
			'attribute' => 'name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->name ? $model->title->message : '-';
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->desc ? $model->description->message : '-';
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'creation_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['default'] = [
			'attribute' => 'default',
			'filter' => GridView::getFilterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['level/default', 'id'=>$model->primaryKey]);
				return GridView::getHeadline($url, $model->default, 'default');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['message_limit'] = 'message_limit';
		$this->templateColumns['profile_privacy'] = 'profile_privacy';
		$this->templateColumns['profile_comments'] = 'profile_comments';
		$this->templateColumns['photo_size'] = 'photo_size';
		$this->templateColumns['photo_exts'] = 'photo_exts';
		$this->templateColumns['slug'] = 'slug';
		$this->templateColumns['signup'] = [
			'attribute' => 'signup',
			'value' => function($model, $key, $index, $column) {
				return $model->signup;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['message_allow'] = [
			'attribute' => 'message_allow',
			'value' => function($model, $key, $index, $column) {
				return $model->message_allow;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_block'] = [
			'attribute' => 'profile_block',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_block;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_search'] = [
			'attribute' => 'profile_search',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_search;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_style'] = [
			'attribute' => 'profile_style',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_style;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_style_sample'] = [
			'attribute' => 'profile_style_sample',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_style_sample;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_status'] = [
			'attribute' => 'profile_status',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_status;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_invisible'] = [
			'attribute' => 'profile_invisible',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_invisible;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_views'] = [
			'attribute' => 'profile_views',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_views;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_change'] = [
			'attribute' => 'profile_change',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_change;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_delete'] = [
			'attribute' => 'profile_delete',
			'value' => function($model, $key, $index, $column) {
				return $model->profile_delete;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['photo_allow'] = [
			'attribute' => 'photo_allow',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_allow;
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * function getLevel
	 */
	public static function getLevel() {
		$items = [];
		$model = self::find()->orderBy('name ASC')->all();
		if($model !== null) {
			foreach($model as $val) {
				$items[$val->level_id] = $val->title->message;
			}
		}
		
		return $items;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);
		$location = Utility::getUrlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($this->isNewRecord || (!$this->isNewRecord && !$this->name)) {
				$name = new SourceMessage();
				$name->location = $location.'_name';
				$name->message = $this->name_i;
				if($name->save())
					$this->name = $name->id;
				
			} else {
				if($action == 'update') {
					$name = SourceMessage::findOne($this->name);
					$name->message = $this->name_i;
					$name->save();
				}
			}

			if($this->isNewRecord || (!$this->isNewRecord && !$this->desc)) {
				$desc = new SourceMessage();
				$desc->location = $location.'_desc';
				$desc->message = $this->desc_i;
				if($desc->save())
					$this->desc = $desc->id;
				
			} else {
				if($action == 'update') {
					$desc = SourceMessage::findOne($this->desc);
					$desc->message = $this->desc_i;
					$desc->save();
				}
			}
		}
				
		if($action == 'user') {
			$this->profile_privacy = serialize($this->profile_privacy);
			$this->profile_comments = serialize($this->profile_comments);
			$this->photo_size = serialize($this->photo_size);
			$this->photo_exts = serialize(Utility::formatFileType($this->photo_exts));
			
		} else if($action == 'message')
			$this->message_limit = serialize($this->message_limit);

		// user level set to default
		if ($this->default == 1) {
			if($this->isNewRecord)
				self::updateAll(['default' => 0]);
			else
				self::updateAll(['default' => 0], 'level_id <> '.$this->level_id);
		}

		return true;	
	}
}
