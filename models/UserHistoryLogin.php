<?php
/**
 * UserHistoryLogin
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 05:37 WIB
 * @modified date 12 November 2018, 23:53 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_history_login".
 *
 * The followings are the available columns in table "ommu_user_history_login":
 * @property integer $id
 * @property integer $user_id
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property string $lastlogin_from
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class UserHistoryLogin extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['lastlogin_from'];

	public $userDisplayname;
	public $userLevel;
	public $email_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_history_login';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['user_id', 'lastlogin_from'], 'required'],
			[['user_id'], 'integer'],
			[['lastlogin_ip', 'lastlogin_from'], 'string', 'max' => 32],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User'),
			'lastlogin_date' => Yii::t('app', 'Lastlogin Date'),
			'lastlogin_ip' => Yii::t('app', 'Lastlogin IP'),
			'lastlogin_from' => Yii::t('app', 'Lastlogin From'),
			'userDisplayname' => Yii::t('app', 'User'),
			'userLevel' => Yii::t('app', 'Level'),
			'email_search' => Yii::t('app', 'Email'),
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
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserHistoryLogin the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserHistoryLogin(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['userLevel'] = [
			'attribute' => 'userLevel',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->level->title->message : '-';
			},
			'filter' => UserLevel::getLevel(),
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['email_search'] = [
			'attribute' => 'email_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? Yii::$app->formatter->asEmail($model->user->email) : '-';
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['lastlogin_date'] = [
			'attribute' => 'lastlogin_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'lastlogin_date'),
		];
		$this->templateColumns['lastlogin_ip'] = [
			'attribute' => 'lastlogin_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_ip;
			},
		];
		$this->templateColumns['lastlogin_from'] = [
			'attribute' => 'lastlogin_from',
			'value' => function($model, $key, $index, $column) {
				return $model->lastlogin_from;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
            $this->lastlogin_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
