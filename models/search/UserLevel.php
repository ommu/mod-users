<?php
/**
 * UserLevel
 * version: 0.0.1
 *
 * UserLevel represents the model behind the search form about `app\modules\user\models\UserLevel`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 8 October 2017, 07:45 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserLevel as UserLevelModel;
//use app\modules\user\models\Users;

class UserLevel extends UserLevelModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['level_id', 'name', 'desc', 'default', 'signup', 'message_allow', 'profile_block', 'profile_search', 'profile_style', 'profile_style_sample', 'profile_status', 'profile_invisible', 'profile_views', 'profile_change', 'profile_delete', 'photo_allow', 'creation_id', 'modified_id'], 'integer'],
			[['message_limit', 'profile_privacy', 'profile_comments', 'photo_size', 'photo_exts', 'creation_date', 'modified_date', 'slug', 'creation_search', 'modified_search', 'name_i', 'desc_i'], 'safe'],
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
		$query = UserLevelModel::find()->alias('t');
		$query->joinWith(['creation creation', 'modified modified', 'title title', 'description description']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['name_i'] = [
			'asc' => ['title.message' => SORT_ASC],
			'desc' => ['title.message' => SORT_DESC],
		];
		$attributes['desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['level_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.level_id' => $this->level_id,
			't.name' => $this->name,
			't.desc' => $this->desc,
			't.default' => $this->default,
			't.signup' => $this->signup,
			't.message_allow' => $this->message_allow,
			't.profile_block' => $this->profile_block,
			't.profile_search' => $this->profile_search,
			't.profile_style' => $this->profile_style,
			't.profile_style_sample' => $this->profile_style_sample,
			't.profile_status' => $this->profile_status,
			't.profile_invisible' => $this->profile_invisible,
			't.profile_views' => $this->profile_views,
			't.profile_change' => $this->profile_change,
			't.profile_delete' => $this->profile_delete,
			't.photo_allow' => $this->photo_allow,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 't.message_limit', $this->message_limit])
			->andFilterWhere(['like', 't.profile_privacy', $this->profile_privacy])
			->andFilterWhere(['like', 't.profile_comments', $this->profile_comments])
			->andFilterWhere(['like', 't.photo_size', $this->photo_size])
			->andFilterWhere(['like', 't.photo_exts', $this->photo_exts])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'creation.displayname', $this->creation_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search])
			->andFilterWhere(['like', 'title.message', $this->name_i])
			->andFilterWhere(['like', 'description.message', $this->desc_i]);

		return $dataProvider;
	}
}
