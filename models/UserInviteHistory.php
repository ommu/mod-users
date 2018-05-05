<?php
/**
 * UserInviteHistory
 * version: 0.0.1
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

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 05:38 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models;

use Yii;
use yii\helpers\Url;
use app\modules\user\models\view\UserInviteHistory as UserInviteHistoryView;
use app\libraries\grid\GridView;

class UserInviteHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['code','invite_ip','expired_date'];

	// Variable Search
	public $user_search;
	public $email_search;
	public $level_search;
	public $inviter_search;
	public $expired_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_invite_history';
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
			[['invite_id', 'code', 'invite_ip'], 'required'],
			[['invite_id'], 'integer'],
			[['invite_date', 'expired_date'], 'safe'],
			[['code'], 'string', 'max' => 16],
			[['invite_ip'], 'string', 'max' => 20],
			[['invite_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserInvites::className(), 'targetAttribute' => ['invite_id' => 'invite_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInvite()
	{
		return $this->hasOne(UserInvites::className(), ['invite_id' => 'invite_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(UserInviteHistoryView::className(), ['id' => 'id']);
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
			'invite_ip' => Yii::t('app', 'Invite Ip'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'invite_search' => Yii::t('app', 'Invite'),
			'user_search' => Yii::t('app', 'User'),
			'email_search' => Yii::t('app', 'Email'),
			'level_search' => Yii::t('app', 'Level'),
			'inviter_search' => Yii::t('app', 'Inviter'),
			'expired_search' => Yii::t('app', 'Expired'),
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
		if(!Yii::$app->request->get('invite')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return $model->invite->newsletter->user_id ? $model->invite->newsletter->user->displayname : '-';
				},
			];
			$this->templateColumns['email_search'] = [
				'attribute' => 'email_search',
				'value' => function($model, $key, $index, $column) {
					return $model->invite->newsletter->email;
				},
			];
			$this->templateColumns['inviter_search'] = [
				'attribute' => 'inviter_search',
				'value' => function($model, $key, $index, $column) {
					return $model->invite->user_id ? $model->invite->user->displayname : '-';
				},
			];
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return $model->invite->user_id ? $model->invite->user->level->title->message : '-';
				},
			];
		}
		$this->templateColumns['code'] = 'code';
		$this->templateColumns['invite_date'] = [
			'attribute' => 'invite_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'invite_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->invite_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->invite_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['invite_ip'] = 'invite_ip';
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'expired_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->expired_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->expired_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['expired_search'] = [
			'attribute' => 'expired_search',
			'filter' => GridView::getFilterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->view->expired == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'contentOptions' => ['class'=>'center'],
		];
	}
}
