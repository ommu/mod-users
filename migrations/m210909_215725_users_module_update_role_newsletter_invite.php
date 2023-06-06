<?php
/**
 * m210909_215725_users_module_update_role_newsletter_invite
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 21:58 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use app\models\Menu;
use mdm\admin\components\Configs;

class m210909_215725_users_module_update_role_newsletter_invite extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['name' => '/users/newsletter/admin/*'], ['name' => '/users/o/newsletter/*']);
            $this->update($tableName, ['name' => '/users/newsletter/admin/index'], ['name' => '/users/o/newsletter/index']);
            $this->update($tableName, ['name' => '/users/newsletter/history/*'], ['name' => '/users/history/newsletter/*']);
            $this->update($tableName, ['name' => '/users/invite/admin/*'], ['name' => '/users/o/invite/*']);
            $this->update($tableName, ['name' => '/users/invite/admin/index'], ['name' => '/users/o/invite/index']);
            $this->update($tableName, ['name' => '/users/invite/history/*'], ['name' => '/users/history/invite/*']);
        }

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['parent' => 'usersModLevelModerator', 'child' => '/users/newsletter/admin/*'], ['parent' => 'usersModLevelModerator', 'child' => '/users/o/newsletter/*']);
            $this->update($tableName, ['parent' => 'usersModLevelModerator', 'child' => '/users/newsletter/history/*'], ['parent' => 'usersModLevelModerator', 'child' => '/users/history/newsletter/*']);
            $this->update($tableName, ['parent' => 'usersModLevelModerator', 'child' => '/users/invite/admin/*'], ['parent' => 'usersModLevelModerator', 'child' => '/users/o/invite/*']);
            $this->update($tableName, ['parent' => 'usersModLevelModerator', 'child' => '/users/invite/history/*'], ['parent' => 'usersModLevelModerator', 'child' => '/users/history/invite/*']);
        }
        
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['name' => 'Invites', 'route' => '/users/invite/admin/index'], ['module' => 'users', 'route' => '/users/o/invite/index']);
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Subscribes', 'users', null, Menu::getParentId('Users#users'), '/users/newsletter/admin/index', null, null],
			]);
        }
	}
}
