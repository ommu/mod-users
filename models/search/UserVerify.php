<?php
/**
 * UserVerify
 *
 * UserVerify represents the model behind the search form about `ommu\users\models\UserVerify`.
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 17 October 2017, 15:00 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\UserVerify as UserVerifyModel;

class UserVerify extends UserVerifyModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['verify_id', 'publish', 'user_id', 'modified_id'], 'integer'],
			[['code', 'verify_date', 'verify_ip', 'expired_date', 'modified_date', 'deleted_date', 'email_i', 'userDisplayname', 'modifiedDisplayname', 'userLevel', 'expired'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = UserVerifyModel::find()->alias('t');
		else
			$query = UserVerifyModel::find()->alias('t')->select($column);
		$query->joinWith([
			'user user', 
			'modified modified',
			'user.level.title level', 
		])
		->groupBy(['verify_id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['email_i'] = [
			'asc' => ['user.email' => SORT_ASC],
			'desc' => ['user.email' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['userLevel'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['verify_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.verify_id' => $this->verify_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.verify_date as date)' => $this->verify_date,
			'cast(t.expired_date as date)' => $this->expired_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.deleted_date as date)' => $this->deleted_date,
			'user.level_id' => isset($params['level']) ? $params['level'] : $this->userLevel,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		if(isset($params['expired']) && $params['expired'] != '') {
			if($this->expired == 1) {
				$query->andWhere(['or', 
					['<>', 't.publish', '1'],
					['and', 
						['t.publish' => 1], 
						['<=', 't.expired_date', Yii::$app->formatter->asTime('now', 'php:Y-m-d H:i:s')],
					],
				]);
			} else if($this->expired == 0) {
				$query->andWhere(['t.publish' => 1])
					->andWhere(['>=', 't.expired_date', Yii::$app->formatter->asTime('now', 'php:Y-m-d H:i:s')]);
			}
		}

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.verify_ip', $this->verify_ip])
			->andFilterWhere(['like', 'user.email', $this->email_i])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
