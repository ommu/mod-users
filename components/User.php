<?php
namespace ommu\users\components;

use Yii;
use ommu\users\models\User as UserModel;

class User extends \yii\web\User
{
	/**
	 * Menyimpan id untuk authentikasi dengan JWT
	 */
	const JWT_UNIQ_ID = 'LLcrvjg0PkdtmckB2';
	const JWT_ISSUER = 'http://ecc.ft.ugm.ac.id';
	const JWT_AUDIENCE = 'http://ecc.ft.ugm.ac.id';
	const EVENT_INVALIDATE_CACHE = 'invalidateCache';

	public function init() {
		parent::init();
	}

	public function getLanguage() {
		if($this->isGuest)
			return '';

		return $this->getIdentity()->language;
	}

	/**
	 * @inheritdoc
	 */
	public function loginByAccessToken($token, $type = null) {
		if(trim($token) == '') {
			return null;
		}

		$identity = UserModel::find()->where(['auth_key' => $token])->one();
		if($identity != null) {
			$userToken = Yii::$app->jwt->getParser()->parse((string) $identity->auth_key);
			$data = Yii::$app->jwt->getValidationData();
			$data->setIssuer(self::JWT_ISSUER);
			$data->setAudience(self::JWT_AUDIENCE);
			$data->setId(self::JWT_UNIQ_ID);
			$data->setCurrentTime(time() + 60);
			if($userToken->validate($data)) {
				return $identity;
			}
		}
		return null;
	}

	protected function afterLogin($identity, $cookieBased, $duration) {
		parent::afterLogin($identity, $cookieBased, $duration);
	}

	protected function beforeLogout($identity) {
		if(parent::beforeLogout($identity)) {
			$this->trigger(self::EVENT_INVALIDATE_CACHE);
		}
		return true;
	}   
}