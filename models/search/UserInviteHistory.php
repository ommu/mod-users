<?php
/**
 * UserInviteHistory
 *
 * UserInviteHistory represents the model behind the search form about `ommu\users\models\UserInviteHistory`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 7 May 2018, 09:01 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\UserInviteHistory as UserInviteHistoryModel;

class UserInviteHistory extends UserInviteHistoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'invite_id'], 'integer'],
			[['code', 'invite_date', 'invite_ip', 'expired_date',
				'user_search', 'email_search', 'level_search', 'inviter_search', 'expired_search'], 'safe'],
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
			'view view',
			'invite invite',
			'invite.newsletter newsletter',
			'invite.newsletter.user user',
			'invite.user inviter',
			'invite.user.level.title level',
		]);

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
			'asc' => ['newsletter.email' => SORT_ASC],
			'desc' => ['newsletter.email' => SORT_DESC],
		];
		$attributes['inviter_search'] = [
			'asc' => ['inviter.displayname' => SORT_ASC],
			'desc' => ['inviter.displayname' => SORT_DESC],
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
			'inviter.level_id' => isset($params['level']) ? $params['level'] : $this->level_search,
			'view.expired' => $this->expired_search,
		]);

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.invite_ip', $this->invite_ip])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'newsletter.email', $this->email_search])
			->andFilterWhere(['like', 'inviter.displayname', $this->inviter_search]);

		return $dataProvider;
	}
}
