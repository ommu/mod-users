<?php

namespace app\modules\user\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "swt_users_group".
 *
 * @property integer $id
 * @property string $group_login
 * @property string $group_name
 * @property string $name
 * @property string $params
 *
 * @property SwtUsers[] $swtUsers
 */
class UserGroup extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'swt_user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_login', 'params'], 'string'],
            [['group_name', 'name'], 'required'],
            [['group_name', 'name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('base', 'ID'),
            'group_login' => Yii::t('UserModule.models_UserGroup', 'Group Login'),
            'group_name'  => Yii::t('UserModule.models_UserGroup', 'Group Name'),
            'name'        => Yii::t('base', 'Name'),
            'params'      => Yii::t('base', 'Params'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['users_group_id' => 'id']);
    }

    public function getDeserializeParams() {
        if(trim($this->params) == '-' || trim($this->params) == '') return [];
        else {
            return unserialize($this->params);
        }
    }
}
