<?php
/**
 * UserLevel
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 8 October 2017, 07:43 WIB
 * @modified date 2 May 2018, 13:29 WIB
 * @link https://ecc.ft.ugm.ac.id
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
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use ommu\users\models\view\UserLevel as UserLevelView;

class UserLevel extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\GridViewTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['desc_i','creation_search','message_allow','message_limit','profile_block','profile_search','profile_privacy','profile_comments','profile_style','profile_style_sample','profile_status','profile_invisible','profile_views','profile_change','profile_delete','photo_allow','photo_size','photo_exts','slug','modified_date','modified_search'];
	public $name_i;
	public $desc_i;

	// Variable Search
	public $creation_search;
	public $modified_search;
	public $active_search;
	public $pending_search;
	public $noverified_search;
	public $blocked_search;
	public $user_search;

	const SCENARIO_INFO = 'info';
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
			[['default', 'message_allow', 'message_limit', 'profile_block', 'profile_search', 'profile_privacy', 'profile_comments', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'photo_size', 'photo_exts', 'name_i', 'desc_i'], 'required'],
			[['name', 'desc', 'default', 'signup', 'message_allow', 'profile_block', 'profile_search', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'creation_id', 'modified_id'], 'integer'],
			[['photo_exts', 'name_i', 'desc_i'], 'string'],
			//[['message_limit', 'profile_privacy', 'profile_comments', 'photo_size', 'photo_exts'], 'serialize'],
			[['creation_date', 'modified_date'], 'safe'],
			[['desc_i'], 'string', 'max' => 128],
			[['slug', 'name_i'], 'string', 'max' => 32],
		];
	}

	// get scenarios
	public function scenarios()
	{
		return [
			self::SCENARIO_INFO => ['default','name_i','name_i'],
			self::SCENARIO_USER => ['profile_block','profile_search','profile_privacy','profile_comments','photo_allow','photo_size','photo_exts','profile_style','profile_style_sample','profile_status','profile_invisible','profile_views','profile_change','profile_delete'],
			self::SCENARIO_MESSAGE => ['message_allow','message_limit'],
		];
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
			'message_allow' => Yii::t('attribute', 'Can users block other users?'),
			'message_limit' => Yii::t('attribute', 'Message Limit'),
			'message_limit[i]' => Yii::t('app', 'Message Limit'),
			'message_limit[inbox]' => Yii::t('app', 'Inbox'),
			'message_limit[outbox]' => Yii::t('app', 'Outbox'),
			'profile_block' => Yii::t('attribute', 'Can users block other users?'),
			'profile_search' => Yii::t('attribute', 'Search Privacy Options'),
			'profile_privacy' => Yii::t('attribute', 'Profile Privacy'),
			'profile_comments' => Yii::t('attribute', 'Profile Comments'),
			'profile_style' => Yii::t('app', 'Profile Style'),
			'profile_style_sample' => Yii::t('attribute', 'Profile Style Sample'),
			'profile_status' => Yii::t('attribute', 'Allow profile status messages?'),
			'profile_invisible' => Yii::t('attribute', 'Allow users to go invisible?'),
			'profile_views' => Yii::t('attribute', 'Allow users to see who viewed their profile?'),
			'profile_change' => Yii::t('attribute', 'Allow username change?'),
			'profile_delete' => Yii::t('attribute', 'Allow account deletion?'),
			'photo_allow' => Yii::t('attribute', 'Allow User Photos?'),
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
			'active_search' => Yii::t('app', 'Active'),
			'pending_search' => Yii::t('app', 'Pending'),
			'noverified_search' => Yii::t('app', 'Noverified'),
			'blocked_search' => Yii::t('app', 'Blocked'),
			'user_search' => Yii::t('app', 'Users'),
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
	 * @inheritdoc
	 * @return \ommu\users\models\query\UserLevelQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserLevelQuery(get_called_class());
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
				return isset($model->title) ? $model->title->message : '-';
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return isset($model->description) ? $model->description->message : '-';
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
			'filter' => Html::input('date', 'creation_date', Yii::$app->request->get('creation_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-';
			},
			'format' => 'html',
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
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['message_allow'] = [
			'attribute' => 'message_allow',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->message_allow ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_block'] = [
			'attribute' => 'profile_block',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_block ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_search'] = [
			'attribute' => 'profile_search',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_search ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_style'] = [
			'attribute' => 'profile_style',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_style ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_style_sample'] = [
			'attribute' => 'profile_style_sample',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_style_sample ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_status'] = [
			'attribute' => 'profile_status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_status ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_invisible'] = [
			'attribute' => 'profile_invisible',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_invisible ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_views'] = [
			'attribute' => 'profile_views',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_views ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_change'] = [
			'attribute' => 'profile_change',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_change ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['profile_delete'] = [
			'attribute' => 'profile_delete',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->profile_delete ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['photo_allow'] = [
			'attribute' => 'photo_allow',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->photo_allow ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['active_search'] = [
			'attribute' => 'active_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['personal/index', 'level'=>$model->primaryKey, 'status'=>'active']);
				return Html::a($model->view->user_active, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['pending_search'] = [
			'attribute' => 'pending_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['personal/index', 'level'=>$model->primaryKey, 'status'=>'pending']);
				return Html::a($model->view->user_pending, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['noverified_search'] = [
			'attribute' => 'noverified_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['personal/index', 'level'=>$model->primaryKey, 'status'=>'noverified']);
				return Html::a($model->view->user_noverified, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['blocked_search'] = [
			'attribute' => 'blocked_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['personal/index', 'level'=>$model->primaryKey, 'status'=>'blocked']);
				return Html::a($model->view->user_blocked, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['user_search'] = [
			'attribute' => 'user_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['personal/index', 'level'=>$model->primaryKey, 'status'=>'all']);
				return Html::a($model->view->user_all, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['default'] = [
			'attribute' => 'default',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['level/default', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->default, 'Default,No', true);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['signup'] = [
			'attribute' => 'signup',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['level/signup', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->signup, 'Enable,Disable');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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
				->where(['level_id' => 1])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getLevel
	 */
	public static function getLevel($type=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.name=title.id');
		if($type == 'member')
			$model->andWhere(['not in', 't.level_id', [1]]);
		if($type == 'admin')
			$model->andWhere(['t.level_id' => 1]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true) {
			$items = [];
			if($model !== null) {
				foreach($model as $val) {
					$items[$val->level_id] = $val->title->message;
				}
				return $items;
			} else
				return false;
		} else 
			return $model;
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
		$this->name_i = isset($this->title) ? $this->title->message : '';
		$this->desc_i = isset($this->description) ? $this->description->message : '';
		$this->message_limit = unserialize($this->message_limit);
		$this->profile_privacy = unserialize($this->profile_privacy);
		$this->profile_comments = unserialize($this->profile_comments);
		$this->photo_size = unserialize($this->photo_size);
		
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
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
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

			//if($this->scenario == 'user') {
				$this->profile_privacy = serialize($this->profile_privacy);
				$this->profile_comments = serialize($this->profile_comments);
				$this->photo_size = serialize($this->photo_size);
				$this->photo_exts = serialize($this->formatFileType($this->photo_exts));

			//} else if($this->scenario == 'message')
				$this->message_limit = serialize($this->message_limit);

			// user level set to default
			if($this->default == 1)
				self::updateAll(['default' => 0], 'level_id <> '.$this->level_id);
		}
		return true;
	}
}
