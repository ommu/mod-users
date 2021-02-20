<?php
/**
 * UserInviteHistory
 *
 * UserInviteHistory represents the model behind the search form about `ommu\users\models\UserInviteHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 October 2017, 08:28 WIB
 * @modified date 13 November 2018, 11:54 WIB
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
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'invite_id'], 'integer'],
			[['code', 'invite_date', 'invite_ip', 'expired_date', 'email_search', 'displayname_search', 'inviter_search', 'userLevel', 'expired_search'], 'safe'],
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
            $query = UserInviteHistoryModel::find()->alias('t');
        } else {
            $query = UserInviteHistoryModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'invite invite',
			'invite.newsletter newsletter',
			'invite.inviter inviter',
			'invite.inviter.level.title level',
			'view view',
		]);

		$query->groupBy(['id']);

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
		$attributes['email_search'] = [
			'asc' => ['newsletter.email' => SORT_ASC],
			'desc' => ['newsletter.email' => SORT_DESC],
		];
		$attributes['displayname_search'] = [
			'asc' => ['invite.displayname' => SORT_ASC],
			'desc' => ['invite.displayname' => SORT_DESC],
		];
		$attributes['inviter_search'] = [
			'asc' => ['inviter.displayname' => SORT_ASC],
			'desc' => ['inviter.displayname' => SORT_DESC],
		];
		$attributes['userLevel'] = [
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

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
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
			'inviter.level_id' => isset($params['level']) ? $params['level'] : $this->userLevel,
			'view.expired' => $this->expired_search,
		]);

		$query->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.invite_ip', $this->invite_ip])
			->andFilterWhere(['like', 'newsletter.email', $this->email_search])
			->andFilterWhere(['like', 'invite.displayname', $this->displayname_search])
			->andFilterWhere(['like', 'inviter.displayname', $this->inviter_search]);

		return $dataProvider;
	}
}
