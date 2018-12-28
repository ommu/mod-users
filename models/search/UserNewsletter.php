<?php
/**
 * UserNewsletter
 *
 * UserNewsletter represents the model behind the search form about `ommu\users\models\UserNewsletter`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 14 November 2018, 01:24 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\UserNewsletter as UserNewsletterModel;

class UserNewsletter extends UserNewsletterModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['newsletter_id', 'status', 'user_id', 'reference_id', 'subscribe_id', 'modified_id'], 'integer'],
			[['email', 'creation_date', 'modified_date', 'updated_date', 'updated_ip', 'user_search', 'reference_search', 'subscribe_search', 'modified_search', 'level_search', 'register_search'], 'safe'],
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
		$query = UserNewsletterModel::find()->alias('t');
		$query->joinWith([
			'user user', 
			'reference reference', 
			'subscribe subscribe', 
			'modified modified',
			'user.level.title level', 
			'view view', 
		]);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['reference_search'] = [
			'asc' => ['reference.displayname' => SORT_ASC],
			'desc' => ['reference.displayname' => SORT_DESC],
		];
		$attributes['subscribe_search'] = [
			'asc' => ['subscribe.displayname' => SORT_ASC],
			'desc' => ['subscribe.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['level_search'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$attributes['register_search'] = [
			'asc' => ['view.register' => SORT_ASC],
			'desc' => ['view.register' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['newsletter_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.newsletter_id' => $this->newsletter_id,
			't.status' => $this->status,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.reference_id' => isset($params['reference']) ? $params['reference'] : $this->reference_id,
			't.subscribe_id' => isset($params['subscribe']) ? $params['subscribe'] : $this->subscribe_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'user.level_id' => isset($params['level']) ? $params['level'] : $this->level_search,
			'view.register' => $this->register_search,
		]);

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 't.updated_ip', $this->updated_ip])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'reference.displayname', $this->reference_search])
			->andFilterWhere(['like', 'subscribe.displayname', $this->subscribe_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
