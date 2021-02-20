<?php
/**
 * UserPhones
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 14 November 2018, 15:16 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "ommu_user_phones".
 *
 * The followings are the available columns in table "ommu_user_phones":
 * @property integer $phone_id
 * @property integer $publish
 * @property integer $verified
 * @property integer $user_id
 * @property string $phone_number
 * @property string $verified_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\users\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class UserPhones extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $userLevel;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_user_phones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['user_id', 'phone_number'], 'required'],
			[['publish', 'verified', 'user_id', 'phone_number', 'creation_id', 'modified_id'], 'integer'],
			[['phone_number'], 'string', 'max' => 20],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'phone_id' => Yii::t('app', 'Phone'),
			'publish' => Yii::t('app', 'Publish'),
			'verified' => Yii::t('app', 'Verified'),
			'user_id' => Yii::t('app', 'User'),
			'phone_number' => Yii::t('app', 'Phone Number'),
			'verified_date' => Yii::t('app', 'Verified Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'userDisplayname' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
	 * {@inheritdoc}
	 * @return \ommu\users\models\query\UserPhones the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\users\models\query\UserPhones(get_called_class());
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
				return isset($model->user->level) ? $model->user->level->title->message : '-';
			},
			'filter' => UserLevel::getLevel(),
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['phone_number'] = [
			'attribute' => 'phone_number',
			'value' => function($model, $key, $index, $column) {
				return $model->phone_number;
			},
		];
		$this->templateColumns['verified_date'] = [
			'attribute' => 'verified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->verified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'verified_date'),
			'format' => 'html',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['verified'] = [
			'attribute' => 'verified',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['verified', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->verified, 'Verified,Unverified');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
            $model = $model->where(['phone_id' => $id])->one();
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
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
