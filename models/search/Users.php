<?php
/**
 * Users
 *
 * Users represents the model behind the search form about `ommu\users\models\Users`.
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 15 November 2018, 07:03 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\Users as UsersModel;
use ommu\users\models\UserLevel;

class Users extends UsersModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id', 'enabled', 'verified', 'level_id', 'language_id', 'deactivate', 'search', 'invisible', 'privacy', 'comments', 'modified_id'], 'integer'],
			[['email', 'username', 'displayname', 'password', 'salt', 'creation_date', 'creation_ip', 'modified_date', 'lastlogin_date', 'lastlogin_ip', 'lastlogin_from', 'update_date', 'update_ip', 'auth_key', 'jwt_claims', 'modified_search'], 'safe'],
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
		if(!($column && is_array($column)))
			$query = UsersModel::find()->alias('t');
		else
			$query = UsersModel::find()->alias('t')->select($column);
		$query->joinWith([
			'level.title level', 
			'languageRltn languageRltn', 
			'modified modified', 
			// 'member member', 
		])
		->groupBy(['user_id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['level_id'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$attributes['language_id'] = [
			'asc' => ['languageRltn.name' => SORT_ASC],
			'desc' => ['languageRltn.name' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['user_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.user_id' => $this->user_id,
			't.enabled' => $this->enabled,
			't.verified' => $this->verified,
			't.language_id' => isset($params['language']) ? $params['language'] : $this->language_id,
			't.deactivate' => $this->deactivate,
			't.search' => $this->search,
			't.invisible' => $this->invisible,
			't.privacy' => $this->privacy,
			't.comments' => $this->comments,
			'cast(t.creation_date as date)' => $this->creation_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.lastlogin_date as date)' => $this->lastlogin_date,
			'cast(t.update_date as date)' => $this->update_date,
		]);

		if(isset($params['level']))
			$query->andFilterWhere(['t.level_id' => $params['level']]);
		else {
			$controller = strtolower(Yii::$app->controller->id);
			if(in_array($controller, ['admin','member'])) {
				$level = UserLevel::getLevel($controller == 'admin' ? 'admin' : 'member');
				$query->andFilterWhere(['in', 't.level_id', array_flip($level)])
					->andFilterWhere(['t.level_id' => $this->level_id]);
			} else
				$query->andFilterWhere(['t.level_id' => $this->level_id]);
		}

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 't.username', $this->username])
			->andFilterWhere(['like', 't.displayname', $this->displayname])
			->andFilterWhere(['like', 't.password', $this->password])
			->andFilterWhere(['like', 't.salt', $this->salt])
			->andFilterWhere(['like', 't.creation_ip', $this->creation_ip])
			->andFilterWhere(['like', 't.lastlogin_ip', $this->lastlogin_ip])
			->andFilterWhere(['like', 't.lastlogin_from', $this->lastlogin_from])
			->andFilterWhere(['like', 't.update_ip', $this->update_ip])
			->andFilterWhere(['like', 't.auth_key', $this->auth_key])
			->andFilterWhere(['like', 't.jwt_claims', $this->jwt_claims])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
