<?php
/**
 * UserNewsletter
 *
 * UserNewsletter represents the model behind the search form about `app\modules\user\models\UserNewsletter`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 15:59 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserNewsletter as UserNewsletterModel;

class UserNewsletter extends UserNewsletterModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['newsletter_id', 'status', 'user_id', 'reference_id', 'subscribe_id', 'modified_id'], 'integer'],
			[['email', 'subscribe_date', 'modified_date', 'updated_date', 'updated_ip',
				'level_search', 'user_search', 'reference_search', 'subscribe_search', 'modified_search'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
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
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = UserNewsletterModel::find()->alias('t');
		$query->joinWith([
			'view view', 
			'user user', 
			'user.level.title level', 
			'reference reference', 
			'subscribe subscribe', 
			'modified modified'
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['level_search'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
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
			'cast(t.subscribe_date as date)' => $this->subscribe_date,
			't.subscribe_id' => $this->subscribe_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'user.level_id' => isset($params['level']) ? $params['level'] : $this->level_search,
		]);

		/*
		if(isset($params['register_search']) && $params['register_search'] != '') {
			if($params['register_search'] == 1)
				$query->andFilterWhere(['is not', 't.user_id', null]);
			else if($params['register_search'] == 0)
				$query->andFilterWhere(['is', 't.user_id', null]);
		}
		*/

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 't.updated_ip', $this->updated_ip])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'reference.displayname', $this->reference_search])
			->andFilterWhere(['like', 'subscribe.displayname', $this->subscribe_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
