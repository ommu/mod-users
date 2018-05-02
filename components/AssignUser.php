<?php
namespace app\modules\user\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use Yii;

class AssignUser extends Behavior
{
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    public function afterInsert($event) {
        $manager = Yii::$app->authManager;
        $item    = $manager->getRole('user');
        $manager->assign($item, $this->owner->id);
    }
}