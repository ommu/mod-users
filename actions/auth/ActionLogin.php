<?php

namespace app\modules\user\actions\auth;

use Yii;
use app\modules\user\models\LoginForm;
use app\modules\user\components\User;
use app\modules\user\models\User as UserModel;

class ActionLogin extends \app\components\api\Action
{
    private $_shortErr = [];
    private $_listErr  = [];

    public function run() {
        $this->forcePost();

        $result = ['error' => 1, 'message' => 'Tidak dapat memproses permintaan Anda.', 'token' => '',
            'list_error' => [], 'short_error' => []];

        $model = new LoginForm();
        $model->attributes = Yii::$app->request->getBodyParams();
        if($model->validate()) {
            $token = (string)$this->getGeneratedToken($model->getUser()->id);
            $result['token']   = $token;
            $result['error']   = 0;
            $result['message'] = 'Login sukses';

        }else {
            $this->parseError($model->errors);            
            $result['short_error'] = $this->_shortErr;
            $result['message']     = 'Validasi akun gagal!';
            $result['list_error']  = $this->_listErr;
            return $result;
        }
        return $result;
    }

    /**
     * Generate Json Web Token untuk mengakses resource ECC
     * TODO: ganti issuer dan audience dengan domain ecc. misalnya eccugm..com
     *
     * @param int $id
     */
    protected function getGeneratedToken($id) {
        $issuedAt = time();
        $token = Yii::$app->jwt->getBuilder()->setIssuer(User::JWT_ISSUER)
            ->setAudience(User::JWT_AUDIENCE)
            ->setId(User::JWT_UNIQ_ID, true)
            ->setIssuedAt($issuedAt)
            ->setNotBefore(time() + 60)
            ->setExpiration(time() + 3600 * 7) // set masa valid token.
            ->set('uid', $id) // simpan user id di token.
            ->getToken();

        UserModel::getDb()->createCommand()->update(UserModel::tableName(), [
            'auth_key' => (string)$token,
            'auth_key_issued_at' => $issuedAt], 'id= :uid', [':uid' => $id])->execute();
        return $token;
    }

    private function parseError($errors) {
        foreach($errors as $attribute => $errVal) {
            $this->_listErr[$attribute]['error']       = $errVal;
            $this->_listErr[$attribute]['error_count'] = count($errVal);
            $this->_shortErr[]                         = implode('', $errVal);
        }
    }
}