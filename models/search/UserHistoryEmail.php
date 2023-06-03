<?php
/**
 * UserHistoryEmail
 *
 * UserHistoryEmail represents the model behind the search form about `ommu\users\models\UserHistoryEmail`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 October 2017, 05:36 WIB
 * @modified date 13 November 2018, 00:03 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

namespace ommu\users\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\users\models\UserHistoryEmail as UserHistoryEmailModel;

class UserHistoryEmail extends UserHistoryEmailModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'user_id'], 'integer'],
			[['email', 'update_date', 'userDisplayname', 'userLevel'], 'safe'],
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
            $query = UserHistoryEmailModel::find()->alias('t');
        } else {
            $query = UserHistoryEmailModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			'user user',
			'user.level.title level',
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
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['userLevel'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
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
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.update_date as date)' => $this->update_date,
			'user.level_id' => isset($params['level']) ? $params['level'] : $this->userLevel,
		]);

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
