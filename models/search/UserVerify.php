<?php
/**
 * UserVerify
 * version: 0.0.1
 *
 * UserVerify represents the model behind the search form about `app\coremodules\user\models\UserVerify`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 17 October 2017, 15:00 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\coremodules\user\models\UserVerify as UserVerifyModel;
//use app\coremodules\user\models\Users;

class UserVerify extends UserVerifyModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['verify_id', 'publish', 'user_id', 'modified_id'], 'integer'],
			[['code', 'verify_date', 'verify_ip', 'expired_date', 'modified_date', 'deleted_date', 'user_search', 'modified_search', 'email_search', 'expired_search', 'level_search'], 'safe'],
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
		$query = UserVerifyModel::find()->alias('t');
		$query->joinWith(['user user', 'modified modified', 'view view', 'user.level.title level_title']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['email_search'] = [
			'asc' => ['user.email' => SORT_ASC],
			'desc' => ['user.email' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['expired_search'] = [
			'asc' => ['view.expired' => SORT_ASC],
			'desc' => ['view.expired' => SORT_DESC],
		];
		$attributes['level_search'] = [
			'asc' => ['level_title.message' => SORT_ASC],
			'desc' => ['level_title.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['verify_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.verify_id' => $this->verify_id,
			't.publish' => $this->publish,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.verify_date as date)' => $this->verify_date,
			'cast(t.expired_date as date)' => $this->expired_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.deleted_date as date)' => $this->deleted_date,
			'view.expired' => $this->expired_search,
			'user.level_id' => $this->level_search,
		]);

		if(!isset($params['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.verify_ip', $this->verify_ip])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'user.email', $this->email_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
