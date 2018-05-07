<?php
/**
 * UserInviteHistory
 *
 * UserInviteHistory represents the model behind the search form about `app\modules\user\models\UserInviteHistory`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 09:01 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserInviteHistory as UserInviteHistoryModel;

class UserInviteHistory extends UserInviteHistoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'invite_id'], 'integer'],
			[['code', 'invite_date', 'invite_ip', 'expired_date', 'invite_search'], 'safe'],
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
		$query = UserInviteHistoryModel::find()->alias('t');
		$query->joinWith([
			'invite.newsletter.user invite'
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['invite_search'] = [
			'asc' => ['invite.username' => SORT_ASC],
			'desc' => ['invite.username' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.invite_id' => isset($params['invite']) ? $params['invite'] : $this->invite_id,
			'cast(t.invite_date as date)' => $this->invite_date,
			'cast(t.expired_date as date)' => $this->expired_date,
		]);

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.invite_ip', $this->invite_ip])
			->andFilterWhere(['like', 'invite.username', $this->invite_search]);

		return $dataProvider;
	}
}
