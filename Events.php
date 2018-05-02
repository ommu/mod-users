<?php
namespace app\coremodules\user;

use yii\base\Event;
use Yii;
use mdm\admin\models\Route;
use mdm\admin\components\Configs;
use yii\caching\TagDependency;

class Events extends \yii\base\BaseObject
{
    /**
     * Sebelum user logout clear cache menu dan rute.
     */
    public static function onBeforeLogout($event) {
        Route::invalidate();
        if (Configs::cache() !== null) {
            TagDependency::invalidate(Configs::cache(), Configs::CACHE_TAG);
        }
    }
}