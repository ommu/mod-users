<?php
/**
 * UserInvites
 * version: 0.0.1
 *
 * UserInvites represents the model behind the search form about `app\modules\user\models\UserInvites`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Agus Susilo <smartgdi@gmail.com>
 * @created date 17 April 2018, 09:39 WIB
 *
 */

namespace app\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\Users as UsersModel;

class Users extends UsersModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
            [['user_id', 'enabled', 'verified', 'level_id', 'language_id', 'deactivate',
                'search', 'invisible', 'privacy', 'modified_id', '_id'], 'integer'],
            [['email', 'username', 'first_name', 'last_name', 'displayname', 'photos'],
                'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
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
		$query = UsersModel::find()->alias('t');
		$query->joinWith(['modified modified']);

		$dataParams = ['query' => $query];
        if(isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		// $attributes['newsletter_search'] = [
		// 	'asc' => ['newsletter.newsletter_id' => SORT_ASC],
		// 	'desc' => ['newsletter.newsletter_id' => SORT_DESC],
		// ];
		// $attributes['user_search'] = [
		// 	'asc' => ['user.displayname' => SORT_ASC],
		// 	'desc' => ['user.displayname' => SORT_DESC],
		// ];
		// $attributes['modified_search'] = [
		// 	'asc' => ['modified.displayname' => SORT_ASC],
		// 	'desc' => ['modified.displayname' => SORT_DESC],
		// ];
		// $dataProvider->setSort([
		// 	'attributes' => $attributes,
		// 	'defaultOrder' => ['invite_id' => SORT_DESC],
		// ]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.user_id' => $this->user_id,
			't.enabled' => $this->enabled,
			't.verified' => $this->verified,
			't.level_id' => $this->level_id,
			't.language_id' => $this->language_id,
			't.comments' => $this->comments,
			't.deactivate' => $this->deactivate,
			't.search' => $this->search,
			't.invisible' => $this->invisible,
			't.privacy' => $this->privacy,
			't.modified_id' => $this->modified_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			'cast(t.update_date as date)' => $this->update_date,
		]);

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 't.username', $this->username])
			->andFilterWhere(['like', 't.first_name', $this->first_name])
			->andFilterWhere(['like', 't.last_name', $this->last_name])
			->andFilterWhere(['like', 't.displayname', $this->displayname]);

		return $dataProvider;
	}
}
