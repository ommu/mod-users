<?php
/**
 * UserPhones
 *
 * UserPhones represents the model behind the search form about `ommu\users\models\UserPhones`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 14 November 2018, 15:16 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\UserPhones as UserPhonesModel;

class UserPhones extends UserPhonesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['phone_id', 'publish', 'verified', 'user_id', 'creation_id', 'modified_id'], 'integer'],
			[['phone_number', 'verified_date', 'creation_date', 'modified_date', 'updated_date', 'userDisplayname', 'creationDisplayname', 'modifiedDisplayname', 'userLevel'], 'safe'],
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
        if (!($column && is_array($column))) {
            $query = UserPhonesModel::find()->alias('t');
        } else {
            $query = UserPhonesModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'user user', 
			'creation creation', 
			'modified modified',
			'user.level.title level',
		]);

		$query->groupBy(['phone_id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
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
			'defaultOrder' => ['phone_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.phone_id' => $this->phone_id,
			't.verified' => $this->verified,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.verified_date as date)' => $this->verified_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'user.level_id' => isset($params['level']) ? $params['level'] : $this->userLevel,
		]);

        if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

		$query->andFilterWhere(['like', 't.phone_number', $this->phone_number])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
