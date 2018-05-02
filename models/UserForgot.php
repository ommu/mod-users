<?php
/**
 * UserForgot
 * version: 0.0.1
 *
 * This is the model class for table "ommu_user_forgot".
 *
 * The followings are the available columns in table "ommu_user_forgot":
 * @property integer $forgot_id
 * @property integer $publish
 * @property integer $user_id
 * @property string $code
 * @property string $forgot_date
 * @property string $forgot_ip
 * @property string $expired_date
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $deleted_date
 *
 * The followings are the available model relations:
 * @property Users $user

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 17 October 2017, 14:17 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;
use app\libraries\grid\GridView;
use app\coremodules\user\models\view\UserForgot as UserForgotView;

class UserForgot extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date', 'modified_search', 'deleted_date', 'forgot_ip', 'expired_date'];

	// Variable Search
	public $level_search;
	public $user_search;
	public $email_search;
	public $modified_search;
	public $expired_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_forgot';
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
			[['publish', 'user_id', 'modified_id'], 'integer'],
			[['user_id', 'code', 'forgot_ip', 'modified_id'], 'required'],
			[['forgot_date', 'expired_date', 'modified_date', 'deleted_date'], 'safe'],
			[['code'], 'string', 'max' => 64],
			[['forgot_ip'], 'string', 'max' => 20],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
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
		return $this->hasOne(UserForgotView::className(), ['forgot_id' => 'forgot_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'forgot_id' => Yii::t('app', 'Forgot'),
			'publish' => Yii::t('app', 'Publish'),
			'user_id' => Yii::t('app', 'User'),
			'code' => Yii::t('app', 'Code'),
			'forgot_date' => Yii::t('app', 'Forgot Date'),
			'forgot_ip' => Yii::t('app', 'Forgot Ip'),
			'expired_date' => Yii::t('app', 'Expired Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'deleted_date' => Yii::t('app', 'Deleted Date'),
			'level_search' => Yii::t('app', 'Level'),
			'user_search' => Yii::t('app', 'User'),
			'email_search' => Yii::t('app', 'Email'),
			'modified_search' => Yii::t('app', 'Modified'),
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
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['level_search'] = [
				'attribute' => 'level_search',
				'filter' => UserLevel::getLevel(),
				'value' => function($model, $key, $index, $column) {
					return $model->user->level->title->message;
				},
			];
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
			$this->templateColumns['email_search'] = [
				'attribute' => 'email_search',
				'value' => function($model, $key, $index, $column) {
					return $model->user->email ? $model->user->email : '-';
				},
			];
		}
		$this->templateColumns['code'] = 'code';
		$this->templateColumns['forgot_date'] = [
			'attribute' => 'forgot_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'forgot_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->forgot_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->forgot_date, 'datetime') : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['forgot_ip'] = 'forgot_ip';
		$this->templateColumns['expired_date'] = [
			'attribute' => 'expired_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'expired_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->expired_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->expired_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
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
		$this->templateColumns['deleted_date'] = [
			'attribute' => 'deleted_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'deleted_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->deleted_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->deleted_date, 'date'/*datetime*/) : '-';
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
			'format' => 'raw',
		];
	}

	/**
	 * User forgot password codes
	 */
	public static function getUniqueCode() {
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*time());
		$i = 0;
		$code = '' ;

		while ($i <= 31) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 2);
			$code = $code . $tmp; 
			$i++;
		}

		return $code;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->code = self::getUniqueCode();
				$this->forgot_ip = Yii::$app->request->userIP;

			} else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);
		// send email fuinction after forgot
	}
}
