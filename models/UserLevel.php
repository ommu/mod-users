<?php
/**
 * UserLevel
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 October 2017, 07:43 WIB
 * @modified date 9 November 2018, 09:06 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_level".
 *
 * The followings are the available columns in table "ommu_user_level":
 * @property integer $level_id
 * @property integer $name
 * @property integer $desc
 * @property integer $default
 * @property integer $signup
 * @property string $assignment_roles
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
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use ommu\users\models\view\UserLevel as UserLevelView;

class UserLevel extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['desc_i','assignment_roles','message_allow','message_limit','profile_block','profile_search','profile_privacy','profile_comments','profile_style','profile_style_sample','profile_status','profile_invisible','profile_views','profile_change','profile_delete','photo_allow','photo_size','photo_exts','creation_date','creation_search','modified_date','modified_search','slug'];
	public $name_i;
	public $desc_i;

	public $creation_search;
	public $modified_search;

	const SCENARIO_USER = 'user';
	const SCENARIO_MESSAGE = 'message';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_level';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title.message',
				'immutable' => true,
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
			[['default', 'name_i', 'desc_i'], 'required'],
			[['assignment_roles', 'profile_block', 'profile_search', 'profile_privacy', 'profile_comments', 'photo_allow', 'photo_size', 'photo_exts', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete'], 'required', 'on' => self::SCENARIO_USER],
			[['message_allow', 'message_limit'], 'required', 'on' => self::SCENARIO_MESSAGE],
			[['name', 'desc', 'default', 'signup', 'message_allow', 'profile_block', 'profile_search', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'creation_id', 'modified_id'], 'integer'],
			[['photo_exts', 'name_i', 'desc_i'], 'string'],
			//[['message_limit', 'profile_privacy', 'profile_comments', 'photo_size', 'photo_exts'], 'serialize'],
			[['slug', 'name_i'], 'string', 'max' => 64],
			[['desc_i'], 'string', 'max' => 128],
			[['slug'], 'string', 'max' => 32],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_USER] = ['assignment_roles','profile_block','profile_search','profile_privacy','profile_comments','photo_allow','photo_size','photo_exts','profile_style','profile_style_sample','profile_status','profile_invisible','profile_views','profile_change','profile_delete'];
		$scenarios[self::SCENARIO_MESSAGE] = ['message_allow','message_limit'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'level_id' => Yii::t('app', 'Level'),
			'name' => Yii::t('app', 'Level'),
			'desc' => Yii::t('app', 'Description'),
			'default' => Yii::t('app', 'Default'),
			'signup' => Yii::t('app', 'Signup'),
			'assignment_roles' => Yii::t('app', 'Assignment Roles'),
			'message_allow' => Yii::t('app', 'Can users block other users?'),
			'message_limit' => Yii::t('app', 'Message Limit'),
			'profile_block' => Yii::t('app', 'Can users block other users?'),
			'profile_search' => Yii::t('app', 'Search Privacy Options'),
			'profile_privacy' => Yii::t('app', 'Profile Privacy'),
			'profile_comments' => Yii::t('app', 'Profile Comments'),
			'profile_style' => Yii::t('app', 'Profile Style'),
			'profile_style_sample' => Yii::t('app', 'Profile Style Sample'),
			'profile_status' => Yii::t('app', 'Allow profile status messages?'),
			'profile_invisible' => Yii::t('app', 'Allow users to go invisible?'),
			'profile_views' => Yii::t('app', 'Allow users to see who viewed their profile?'),
			'profile_change' => Yii::t('app', 'Allow username change?'),
			'profile_delete' => Yii::t('app', 'Allow account deletion?'),
			'photo_allow' => Yii::t('app', 'Allow User Photos?'),
			'photo_size' => Yii::t('app', 'Photo Size'),
			'photo_exts' => Yii::t('app', 'Photo Ext'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'slug' => Yii::t('app', 'Slug'),
			'name_i' => Yii::t('app', 'Level'),
			'desc_i' => Yii::t('app', 'Description'),
			'creation_search' => Yii::t('app', 'Creation'),
			'modified_search' => Yii::t('app', 'Modified'),
			'photo_size[i]' => Yii::t('app', 'Photo Size'),
			'photo_size[width]' => Yii::t('app', 'Photo Width'),
			'photo_size[height]' => Yii::t('app', 'Photo Height'),
			'message_limit[i]' => Yii::t('app', 'Message Limit'),
			'message_limit[inbox]' => Yii::t('app', 'Message Inbox'),
			'message_limit[outbox]' => Yii::t('app', 'Message Outbox'),
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
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserLevel the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserLevel(get_called_class());
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
				return $model->name_i;
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->desc_i;
			},
		];
		$this->templateColumns['assignment_roles'] = [
			'attribute' => 'assignment_roles',
			'value' => function($model, $key, $index, $column) {
				return $this->formatFileType($model->assignment_roles, false);
			},
		];
		$this->templateColumns['message_limit'] = [
			'attribute' => 'message_limit',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->message_limit);
			},
		];
		$this->templateColumns['profile_privacy'] = [
			'attribute' => 'profile_privacy',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->profile_privacy);
			},
		];
		$this->templateColumns['profile_comments'] = [
			'attribute' => 'profile_comments',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->profile_comments);
			},
		];
		$this->templateColumns['photo_size'] = [
			'attribute' => 'photo_size',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->photo_size);
			},
		];
		$this->templateColumns['photo_exts'] = [
			'attribute' => 'photo_exts',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_exts;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creation_search'] = [
				'attribute' => 'creation_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
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
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['message_allow'] = [
			'attribute' => 'message_allow',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->message_allow);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_block'] = [
			'attribute' => 'profile_block',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_block);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_search'] = [
			'attribute' => 'profile_search',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_search);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_style'] = [
			'attribute' => 'profile_style',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_style);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_style_sample'] = [
			'attribute' => 'profile_style_sample',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_style_sample);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_status'] = [
			'attribute' => 'profile_status',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_status);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_invisible'] = [
			'attribute' => 'profile_invisible',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_invisible);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_views'] = [
			'attribute' => 'profile_views',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_views);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_change'] = [
			'attribute' => 'profile_change',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_change);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_delete'] = [
			'attribute' => 'profile_delete',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->profile_delete);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['photo_allow'] = [
			'attribute' => 'photo_allow',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->photo_allow);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['user_active'] = [
			'attribute' => 'view.user_active',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->user_active, ['member/index', 'level'=>$model->primaryKey, 'status'=>'active']);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['user_pending'] = [
			'attribute' => 'view.user_pending',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->user_pending, ['member/index', 'level'=>$model->primaryKey, 'status'=>'pending']);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['user_noverified'] = [
			'attribute' => 'view.user_noverified',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->user_noverified, ['member/index', 'level'=>$model->primaryKey, 'status'=>'noverified']);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['user_blocked'] = [
			'attribute' => 'view.user_blocked',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->user_blocked, ['member/index', 'level'=>$model->primaryKey, 'status'=>'blocked']);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['user_all'] = [
			'attribute' => 'view.user_all',
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->view->user_all, ['member/index', 'level'=>$model->primaryKey, 'status'=>'all']);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['default'] = [
			'attribute' => 'default',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['setting/level/default', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->default, 'Default,No', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['signup'] = [
			'attribute' => 'signup',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['setting/level/signup', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->signup, 'Enable,Disable');
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
			$model = self::find()
				->select([$column])
				->where(['level_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getLevel
	 */
	public static function getLevel($type=null, $array=true) 
	{
		$model = self::find()->alias('t');
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.name=title.id');
		if($type == 'member')
			$model->andWhere(['not in', 't.level_id', [1,2]]);
		if($type == 'admin')
			$model->andWhere(['in','t.level_id', [1,2]]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'level_id', 'name_i');

		return $model;
	}

	/**
	 * function getMessageAllow
	 */
	public static function getMessageAllow($value=null)
	{
		$items = array(
			2 => Yii::t('app', 'Everyone - users can send private messages to anyone.'),
			1 => Yii::t('app', 'Friends only - users can send private messages to their friends only.'),
			0 => Yii::t('app', 'Nobody - users cannot send private messages.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getMessageLimit
	 */
	public static function getMessageLimit($value=null)
	{
		$items = array(
			5 => 5,
			10 => 10,
			20 => 20,
			30 => 30,
			40 => 40,
			50 => 50,
			100 => 100,
			200 => 200,
			500 => 500,
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileBlock
	 */
	public static function getProfileBlock($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, users can block other users.'),
			0 => Yii::t('app', 'No, users cannot block other users.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileSearch
	 */
	public static function getProfileSearch($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, allow users to exclude themselves from search results. '),
			0 => Yii::t('app', 'No, force all users to be included in search results.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfilePrivacy
	 */
	public static function getProfilePrivacy()
	{
		$items = array(
			1 => Yii::t('app', 'Everyone'),
			2 => Yii::t('app', 'All Registered Users'),
			3 => Yii::t('app', 'Only My Friends and Everyone within My Subnetwork'),
			4 => Yii::t('app', 'Only My Friends and Their Friends within My Subnetwork'),
			5 => Yii::t('app', 'Only My Friends'),
			6 => Yii::t('app', 'Only Me'),
		);

		return $items;
	}

	/**
	 * function getProfileStyle
	 */
	public static function getProfileStyle($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, users can add custom CSS styles to their profiles.'),
			0 => Yii::t('app', 'No, users cannot add custom CSS styles to their profiles.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileStyleSample
	 */
	public static function getProfileStyleSample($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, users can choose from the provided sample CSS.'),
			0 => Yii::t('app', 'No, users can not choose from the provided sample CSS.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileStatus
	 */
	public static function getProfileStatus($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, allow users to have a "status" message.'),
			0 => Yii::t('app', 'No, users cannot have a "status" message.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileInvisible
	 */
	public static function getProfileInvisible($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, allow users to go invisible.'),
			0 => Yii::t('app', 'No, do not allow users to go invisible.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileViews
	 */
	public static function getProfileViews($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, allow users to see who has viewed their profile.'),
			0 => Yii::t('app', 'No, do not allow users to see who has viewed their profile.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileChangeUsername
	 */
	public static function getProfileChangeUsername($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, allow users to change their username.'),
			0 => Yii::t('app', 'No, do not allow users to change their username.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getProfileDeleted
	 */
	public static function getProfileDeleted($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, allow users to delete their account.'),
			0 => Yii::t('app', 'No, do not allow users to delete their account.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getPhotoAllow
	 */
	public static function getPhotoAllow($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, users can upload a photo.'),
			0 => Yii::t('app', 'No, users can not upload a photo.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getSize
	 */
	public static function getSize($banner_size)
	{
		if(empty($banner_size))
			return '-';

		return $banner_size['width'].'x'.$banner_size['height'];
	}

	/**
	 * function getDefault
	 */
	public static function getDefault() 
	{
		$model = self::find()
			->select(['level_id'])
			->where(['default' => 1])
			->one();
			
		return $model->level_id;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->name_i = isset($this->title) ? $this->title->message : '';
		$this->desc_i = isset($this->description) ? $this->description->message : '';

		$this->message_limit = unserialize($this->message_limit);
		$this->profile_privacy = unserialize($this->profile_privacy);
		$this->profile_comments = unserialize($this->profile_comments);
		$this->photo_size = unserialize($this->photo_size);

		$this->assignment_roles = $this->formatFileType($this->assignment_roles, true, '#');

		$photo_exts = unserialize($this->photo_exts);
		if(!empty($photo_exts))
			$this->photo_exts = $this->formatFileType($photo_exts, false);
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->photo_size['width'] == '' && $this->photo_size['height'] == '')
				$this->addError('photo_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_size')]));
			else {
				if($this->photo_size['width'] == '')
					$this->addError('photo_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_size[width]')]));
				else if($this->photo_size['height'] == '')
					$this->addError('photo_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_size[height]')]));
			}

			if($this->message_limit['inbox'] == '' && $this->message_limit['outbox'] == '')
				$this->addError('message_limit', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('message_limit')]));
			else {
				if($this->message_limit['inbox'] == '')
					$this->addError('message_limit', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('message_limit[inbox]')]));
				else if($this->message_limit['outbox'] == '')
					$this->addError('message_limit', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('message_limit[outbox]')]));
			}

			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
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

		$location = $this->urlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->name)) {
				$name = new SourceMessage();
				$name->location = $location.'_title';
				$name->message = $this->name_i;
				if($name->save())
					$this->name = $name->id;

				$this->slug = $this->urlTitle($this->name_i);

			} else {
				$name = SourceMessage::findOne($this->name);
				$name->message = $this->name_i;
				$name->save();
			}

			if($insert || (!$insert && !$this->desc)) {
				$desc = new SourceMessage();
				$desc->location = $location.'_description';
				$desc->message = $this->desc_i;
				if($desc->save())
					$this->desc = $desc->id;

			} else {
				$desc = SourceMessage::findOne($this->desc);
				$desc->message = $this->desc_i;
				$desc->save();
			}

			// if($this->scenario == self::SCENARIO_MESSAGE)
				$this->message_limit = serialize($this->message_limit);

			// } else if($this->scenario == self::SCENARIO_USER) {
				$this->assignment_roles = $this->formatFileType($this->assignment_roles, false, '#');
				$this->profile_privacy = serialize($this->profile_privacy);
				$this->profile_comments = serialize($this->profile_comments);
				$this->photo_size = serialize($this->photo_size);
				$this->photo_exts = serialize($this->formatFileType($this->photo_exts));
			// }

			// user level set to default
			if($this->default == 1)
				self::updateAll(['default' => 0], 'level_id <> '.$this->level_id);
		}
		return true;
	}
}
