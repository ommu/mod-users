<?php
/**
 * UserInviteHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 09:38 WIB
 * @modified date 2 May 2018, 13:17 WIB
 * @link https://github.com/ommu/mod-users
 *
 * This is the model class for table "_user_invite_history".
 *
 * The followings are the available columns in table "_user_invite_history":
 * @property integer $id
 * @property integer $expired
 * @property integer $verify_day_left
 * @property integer $verify_hour_left
 *
 */

namespace ommu\users\models\view;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class UserInviteHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_user_invite_history';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'expired', 'verify_day_left', 'verify_hour_left'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'expired' => Yii::t('app', 'Expired'),
			'verify_day_left' => Yii::t('app', 'Verify Day Left'),
			'verify_hour_left' => Yii::t('app', 'Verify Hour Left'),
		];
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
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return $model->id;
			},
		];
		$this->templateColumns['expired'] = [
			'attribute' => 'expired',
			'value' => function($model, $key, $index, $column) {
				return $model->expired;
			},
		];
		$this->templateColumns['verify_day_left'] = [
			'attribute' => 'verify_day_left',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_day_left;
			},
		];
		$this->templateColumns['verify_hour_left'] = [
			'attribute' => 'verify_hour_left',
			'value' => function($model, $key, $index, $column) {
				return $model->verify_hour_left;
			},
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
