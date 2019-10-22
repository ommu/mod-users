<?php
/**
 * UserInviteHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 October 2017, 05:38 WIB
 * @modified date 13 November 2018, 11:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_invite_history".
 *
 * The followings are the available columns in table "ommu_user_invite_history":
 * @property integer $id
 * @property integer $invite_id
 * @property string $code
 * @property string $invite_date
 * @property string $invite_ip
 * @property string $expired_date
 *
 * The followings are the available model relations:
 * @property UserInvites $invite
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\view\UserInviteHistory as UserInviteHistoryView;

class UserInviteHistory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['code','invite_ip','expired_date'];

	public $email_search;
	public $displayname_search;
	public $inviter_search;
	public $level_search;
	public $expired_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_invite_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['invite_id', 'code'], 'required'],
			[['invite_id'], 'integer'],
			[['code'], 'string', 'max' => 16],
			[['invite_ip'], 'string', 'max' => 20],
			[['invite_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserInvites::className(), 'targetAttribute' => ['invite_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'invite_id' => Yii::t('app', 'Invite'),
			'code' => Yii::t('app', 'Code'),
			'invite_date' => Yii::t('app', 'Invite Date'),
			'invite_ip' => Yii::t('app', 'Invite IP'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'email_search' => Yii::t('app', 'Email'),
			'displayname_search' => Yii::t('app', 'Displayname'),
			'inviter_search' => Yii::t('app', 'Inviter'),
			'level_search' => Yii::t('app', 'Level'),
			'expired_search' => Yii::t('app', 'Expired'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvite()
	{
		return $this->hasOne(UserInvites::className(), ['id' => 'invite_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(UserInviteHistoryView::className(), ['id' => 'id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserInviteHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserInviteHistory(get_called_class());
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
		if(!Yii::$app->request->get('invite')) {
			$this->templateColumns['email_search'] = [
				'attribute' => 'email_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->invite->newsletter) ? Yii::$app->formatter->asEmail($model->invite->newsletter->email) : '-';
				},
				'format' => 'html',
			];
			$this->templateColumns['displayname_search'] = [
				'attribute' => 'displayname_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->invite) ? $model->invite->displayname : '-';
				},
			];
			$this->templateColumns['inviter_search'] = [
				'attribute' => 'inviter_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->invite->inviter) ? $model->invite->inviter->displayname : '-';
				},
			];
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->invite->inviter->level) ? $model->invite->inviter->level->title->message : '-';
				},
				'filter' => UserLevel::getLevel(),
			];
		}
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['invite_date'] = [
			'attribute' => 'invite_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->invite_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'invite_date'),
		];
		$this->templateColumns['invite_ip'] = [
			'attribute' => 'invite_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->invite_ip;
			},
		];
		$this->templateColumns['expired_search'] = [
			'attribute' => 'expired_search',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->view->expired);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->expired_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'expired_date'),
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
