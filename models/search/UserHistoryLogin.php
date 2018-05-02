<?php
/**
 * UserHistoryLogin
 * version: 0.0.1
 *
 * UserHistoryLogin represents the model behind the search form about `app\coremodules\user\models\UserHistoryLogin`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 05:39 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\coremodules\user\models\UserHistoryLogin as UserHistoryLoginModel;
//use app\coremodules\user\models\Users;

class UserHistoryLogin extends UserHistoryLoginModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'user_id'], 'integer'],
			[['lastlogin_date', 'lastlogin_ip', 'lastlogin_from', 'user_search', 'level_search'], 'safe'],
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
		$query = UserHistoryLoginModel::find()->alias('t');
		$query->joinWith(['user user', 'user.level.title level_title']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['level_search'] = [
			'asc' => ['level_title.message' => SORT_ASC],
			'desc' => ['level_title.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.lastlogin_date as date)' => $this->lastlogin_date,
			'user.level_id' => $this->level_search,
		]);

		$query->andFilterWhere(['like', 't.lastlogin_ip', $this->lastlogin_ip])
			->andFilterWhere(['like', 't.lastlogin_from', $this->lastlogin_from])
			->andFilterWhere(['like', 'user.displayname', $this->user_search]);

		return $dataProvider;
	}
}
