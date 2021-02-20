<?php
/**
 * ActionIndex
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 25 November 2018, 13:58 WIB
 * @link https://github.com/ommu/mod-users
 */

namespace ommu\users\actions\login;

use Yii;
use app\modules\user\models\LoginForm;
use yii\validators\EmailValidator;

class ActionIndex extends \app\components\api\Action
{
	use \app\modules\user\components\traits\User;

	private $_shortErr = [];
	private $_listErr  = [];

	public function run()
	{
		$this->forcePost();

		$result = [
			'error' => 1,
			'message' => 'Tidak dapat memproses permintaan Anda.',
			'token' => '',
			'list_error' => [],
			'short_error' => [],
		];

		$model = new LoginForm();
		$model->attributes = Yii::$app->request->getBodyParams();
		$model->isAdmin = true;
		$model->is_api = true;
		$validator = new EmailValidator();
		$email = '';
        if ($validator->validate($model->username) === true) {
			$email = $model->username;
			$model->setByEmail(true);
		}
		
        if ($model->validate() && $model->login()) {
			$claims = unserialize($model->getUser()->jwt_claims);
			$token  = $this->buildJwtTokenFromClaim($claims, $model->getUser()->getId());
			$_token = Yii::$app->jwt->getParser()->parse((string) $token);
            if (!Yii::$app->jwt->validateToken($_token)) {
                $token = $model->getUser()->refreshToken($model->getUser()->getId());
            }
			
			$result['token']   = (string)$token;
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

	private function parseError($errors)
	{
        foreach ($errors as $attribute => $errVal) {
			$this->_listErr[$attribute]['error']       = $errVal;
			$this->_listErr[$attribute]['error_count'] = count($errVal);
			$this->_shortErr[]                         = implode('', $errVal);
		}
	}
}
