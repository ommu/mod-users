<?php
/**
 * UserHistoryEmail
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 05:32 WIB
 * @modified date 12 November 2018, 23:52 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_history_email".
 *
 * The followings are the available columns in table "ommu_user_history_email":
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property string $update_date
 *
 * The followings are the available model relations:
 * @property Users $user
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class UserHistoryEmail extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $userDisplayname;
	public $userLevel;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_history_email';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['user_id', 'email'], 'required'],
			[['user_id'], 'integer'],
			[['email'], 'string', 'max' => 64],
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
			'email' => Yii::t('app', 'Email'),
			'update_date' => Yii::t('app', 'Update Date'),
			'userDisplayname' => Yii::t('app', 'User'),
			'userLevel' => Yii::t('app', 'Level'),
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
	 * @return \ommu\users\models\query\UserHistoryEmail the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserHistoryEmail(get_called_class());
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
			'contentOptions' => ['class' => 'text-center'],
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
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asEmail($model->email);
			},
			'format' => 'html',
		];
		$this->templateColumns['update_date'] = [
			'attribute' => 'update_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->update_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'update_date'),
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
        }
        return true;
	}
}
