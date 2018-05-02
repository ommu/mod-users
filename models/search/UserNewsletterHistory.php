<?php
/**
 * UserNewsletterHistory
 * version: 0.0.1
 *
 * UserNewsletterHistory represents the model behind the search form about `app\coremodules\user\models\UserNewsletterHistory`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 23 October 2017, 08:29 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\coremodules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\coremodules\user\models\UserNewsletterHistory as UserNewsletterHistoryModel;
//use app\coremodules\user\models\UserNewsletter;

class UserNewsletterHistory extends UserNewsletterHistoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'status', 'newsletter_id'], 'integer'],
			[['updated_date', 'updated_ip', 'level_search', 'user_search', 'email_search'], 'safe'],
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
		$query = UserNewsletterHistoryModel::find()->alias('t');
		$query->joinWith(['newsletter newsletter', 'newsletter.user user', 'newsletter.user.level.title level_title']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['level_search'] = [
			'asc' => ['level_title.message' => SORT_ASC],
			'desc' => ['level_title.message' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['email_search'] = [
			'asc' => ['newsletter.email' => SORT_ASC],
			'desc' => ['newsletter.email' => SORT_DESC],
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
			't.status' => $this->status,
			't.newsletter_id' => isset($params['newsletter']) ? $params['newsletter'] : $this->newsletter_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'user.level_id' => $this->level_search,
		]);

		$query->andFilterWhere(['like', 't.updated_ip', $this->updated_ip])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'newsletter.email', $this->email_search]);

		return $dataProvider;
	}
}
