<?php
/**
 * User search model
 *
 * @author Agus Susilo <smartgdi@gmail.com>
 */
namespace app\coremodules\user\models\search;

use \yii\base\Model;
use \yii\data\ActiveDataProvider;

class User extends \app\coremodules\user\models\User
{
    public $groupName;

    public function rules() {
        return [
            [['id'], 'integer'],
            [['username', 'name', 'email', 'groupName'], 'safe'],
        ];
    }

    public function scenarios() {
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

    public function search($params) {
        $query = \app\coremodules\user\models\User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'username',
                'name',
                'email',
                'groupName' => [
                    'asc'   => ['swt_user_group.name' => SORT_ASC],
                    'desc'  => ['swt_user_group.name' => SORT_DESC],
                    'label' => 'Group Name',
                ],
            ]
        ]);

        if(!($this->load($params) && $this->validate())) {
            $query->joinWith(['userGroup']);
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);
        $query->joinWith(['userGroup' => function($q) {
            $q->where('swt_user_group.name LIKE "%' . $this->groupName . '%"');
        }]);

        return $dataProvider;
    }
}
