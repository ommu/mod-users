<?php

namespace app\coremodules\user\models;

use Yii;
use yii\base\NotSupportException;
use yii\web\IdentityInterface;
use app\coremodules\user\models\UserGroup;
use app\coremodules\user\components\User as UserIdentity;
// use yii\filters\RateLimitInterface;

/**
 * This is the model class for table "swt_users".
 *
 * @property integer $id
 * @property integer $users_group_id
 * @property integer $actived
 * @property integer $status_user
 * @property string $name
 * @property string $username
 * @property string $salt
 * @property string $password
 * @property string $email
 * @property integer $block
 * @property string $register_date
 * @property string $last_visit_date
 * @property string $activation
 * @property string $activation_date
 * @property integer $is_online
 * @property string $photo
 * @property string $mobile_no
 * @property integer $period
 *
 * @property SwtUsersGroup $usersGroup
 */
class User extends \app\components\ActiveRecord implements IdentityInterface//, RateLimitInterface
{
    public $language;
    public $status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'swt_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['users_group_id', 'username', 'password', 'email', 'mobile_no'], 'required'],
            [['users_group_id', 'actived', 'status_user', 'block', 'is_online', 'period'], 'integer'],
            [['register_date', 'last_visit_date', 'activation_date', 'auth_key_issued_at'], 'safe'],
            [['username', 'email'], 'unique'],
            [['name'], 'string', 'max' => 60],
            [['username'], 'string', 'max' => 50],
            [['salt'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 200],
            [['guid'], 'string', 'max' => 45],
            [['email', 'activation', 'photo'], 'string', 'max' => 80],
            [['mobile_no'], 'string', 'max' => 15],
            [['users_group_id'], 'exist', 'skipOnError' => true, 
                'targetClass' => UserGroup::className(), 
                'targetAttribute' => ['users_group_id' => 'id']],
            [['language'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('base', 'ID'),
            'users_group_id'  => Yii::t('base', 'Group ID'),
            'actived'         => 'Actived',
            'status_user'     => 'Status User',
            'name'            => Yii::t('base', 'Name'),
            'username'        => 'Username',
            'salt'            => 'Salt',
            'password'        => 'Password',
            'email'           => 'Email',
            'block'           => 'Blok',
            'register_date'   => 'Tanggal Daftar',
            'last_visit_date' => 'Last Visit Date',
            'activation'      => 'Activation',
            'activation_date' => 'Activation Date',
            'is_online'       => 'Is Online',
            'photo'           => 'Photo',
            'mobile_no'       => 'Mobile No',
            'period'          => 'Period',
            'language'        => 'Language',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup()
    {
        return $this->hasOne(UserGroup::className(), ['id' => 'users_group_id']);
    }

    public function getGroupName() {
        return $this->userGroup->name;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByPasswordResetToken($token) {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if($timestamp + $expire < time()) {
            // token expire
            return null;
        }

        return static::findOne(['password_reset_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // return $this->password === sha1($password);
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomKey();
    }

    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomKey() . '_' . time();
    }

    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    // public static function hashPassword($password) {
    //  return Yii::$app->security::generatePasswordHash($password);
    // }

    public function behaviors() {
        return [
            \app\components\behaviors\GUID::className(),
        ];
    }

    public function afterFind() {
        parent::afterFind();

        $this->status = $this->status_user;
    }

    protected function beforeLogout($identity) {
        if(parent::beforeLogout($identity)) {
        }
        return true;
    }

    public static function getNamespace() {
        return __NAMESPACE__;
    }

    /**
     * Refresh user token
     *
     * @param integer $id user id
     * @return string token baru
     */
    public function refreshToken(int $id): string {
        $issuedAt = time();
        $token = Yii::$app->jwt->getBuilder()
            ->setIssuer(UserIdentity::JWT_ISSUER)
            ->setAudience(UserIdentity::JWT_AUDIENCE)
            ->setId(UserIdentity::JWT_UNIQ_ID, true)
            ->setIssuedAt($issuedAt)
            ->setNotBefore(time() + 60)
            ->setExpiration(time() + 3600 * 7)
            ->set('uid', $id)
            ->getToken();

        self::getDb()->createCommand()->update(self::tableName(), [
            'auth_key' => (string)$token,
            'auth_key_issued_at' => $issuedAt], 
            'id= :uid', [':uid' => $id]
        )->execute();
        return $token;
    }

    /**
     * Memeriksa apakah user token sudah expire atau belum
     *
     * @param string $userToken token user yang aktif
     * @return boolean
     */
    public function isTokenExpire(string $userToken): bool {
        $token = Yii::$app->jwt->getParser()->parse((string) $userToken);
        $data = Yii::$app->jwt->getValidationData();
        $data->setIssuer(UserIdentity::JWT_ISSUER);
        $data->setAudience(UserIdentity::JWT_AUDIENCE);
        $data->setId(\app\coremodules\user\components\User::JWT_UNIQ_ID);
        return $token->validate($data);
    }
    // implementasi rate limit untuk api. lihat dokumen guide tentang rest api
    // public function getRateLimit($request, $action) {
    //     return [$this->rateLimit, 1];
    // }

    // public function loadAllowance($request, $action) {
    //     return [$this->allowance, $this->allowance_updated_at];
    // }

    // public function saveAllowance($request, $action, $allowance, $timestamp) {
    //     $this->allowance = $allowance;
    //     $this->allowance_updated_at = $timestamp;
    //     $this->save();
    // }
}
