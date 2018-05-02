<?php
/**
 * UserInviteHistory
 * version: 0.0.1
 *
 * UserInviteHistory represents the model behind the search form about `app\modules\user\models\UserInviteHistory`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 23 October 2017, 08:28 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserInviteHistory as UserInviteHistoryModel;
//use app\modules\user\models\UserInvites;

class UserInviteHistory extends UserInviteHistoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'invite_id'], 'integer'],
			[['code', 'invite_date', 'invite_ip', 'expired_date', 'user_search', 'email_search', 'level_search', 'inviter_search', 'expired_search'], 'safe'],
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
		$query->joinWith(['invite invite', 'view view', 'invite.newsletter newsletter', 'invite.newsletter.user newsletter_user', 'invite.user user', 'invite.user.level.title level_title']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['user_search'] = [
			'asc' => ['newsletter_user.displayname' => SORT_ASC],
			'desc' => ['newsletter_user.displayname' => SORT_DESC],
		];
		$attributes['email_search'] = [
			'asc' => ['newsletter.email' => SORT_ASC],
			'desc' => ['newsletter.email' => SORT_DESC],
		];
		$attributes['level_search'] = [
			'asc' => ['level_title.message' => SORT_ASC],
			'desc' => ['level_title.message' => SORT_DESC],
		];
		$attributes['inviter_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
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

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => isset($params['id']) ? $params['id'] : $this->id,
			't.invite_id' => isset($params['invite']) ? $params['invite'] : $this->invite_id,
			'cast(t.invite_date as date)' => $this->invite_date,
			'cast(t.expired_date as date)' => $this->expired_date,
			'user.level_id' => $this->level_search,
			'view.expired' => $this->expired_search,
		]);

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.invite_ip', $this->invite_ip])
			->andFilterWhere(['like', 'newsletter_user.displayname', $this->user_search])
			->andFilterWhere(['like', 'newsletter.email', $this->email_search])
			->andFilterWhere(['like', 'user.displayname', $this->inviter_search]);

		return $dataProvider;
	}
}
