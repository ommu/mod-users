<?php
/**
 * MemberSuggestAction
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 1 July 2019, 18:20 WIB
 * @link https://github.com/ommu/mod-users
 */

namespace ommu\users\actions;

use Yii;
use ommu\users\models\Users;
use yii\validators\EmailValidator;

class MemberSuggestAction extends \yii\base\Action
{
	/**
	 * {@inheritdoc}
	 */
	protected function beforeRun()
	{
        if (parent::beforeRun()) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			Yii::$app->response->charset = 'UTF-8';
        }
        return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function run()
	{
		$term = utf8_decode(urldecode(Yii::$app->request->get('term')));

        if ($term == null) return [];

		$validator = new EmailValidator();
		$model = Users::find()
            ->alias('t')
			->suggest();
        if ($validator->validate($term) === true) {
			$model->andWhere(['like', 't.email', $term]);
        } else {
			$model->andWhere(['or',
				['like', 't.email', $term],
				['like', 't.displayname', $term]
			]);
		}
		$model = $model->limit(15)->all();

		$result = [];
        foreach ($model as $val) {
			$result[$val->user_id] = [
				'id' => $val->user_id, 
				'email' => trim($val->email),
				'photo' => trim($val->photos),
			];
            if ($val->displayname) {
				$result[$val->user_id]['name'] = trim($val->displayname);
            }
		}
		return array_values($result);
	}
}
