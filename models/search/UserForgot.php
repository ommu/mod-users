<?php
/**
 * UserForgot
 *
 * UserForgot represents the model behind the search form about `ommu\users\models\UserForgot`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 17 October 2017, 15:01 WIB
 * @modified date 14 November 2018, 13:51 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\UserForgot as UserForgotModel;

class UserForgot extends UserForgotModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['forgot_id', 'publish', 'user_id', 'modified_id'], 'integer'],
			[['code', 'forgot_date', 'forgot_ip', 'expired_date', 'modified_date', 'deleted_date', 'email_i', 'user_search', 'modified_search', 'level_search', 'expired_search'], 'safe'],
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
	public function search($params)
	{
		$query = UserForgotModel::find()->alias('t');
		$query->joinWith([
			'user user', 
			'modified modified',
			'user.level.title level', 
			'view view', 
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['email_i'] = [
			'asc' => ['user.email' => SORT_ASC],
			'desc' => ['user.email' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['level_search'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$attributes['expired_search'] = [
			'asc' => ['view.expired' => SORT_ASC],
			'desc' => ['view.expired' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['forgot_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.forgot_id' => $this->forgot_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.forgot_date as date)' => $this->forgot_date,
			'cast(t.expired_date as date)' => $this->expired_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.deleted_date as date)' => $this->deleted_date,
			'user.level_id' => isset($params['level']) ? $params['level'] : $this->level_search,
			'view.expired' => $this->expired_search,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.forgot_ip', $this->forgot_ip])
			->andFilterWhere(['like', 'user.email', $this->email_i])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
