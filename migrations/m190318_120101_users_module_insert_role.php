<?php
/**
 * m190318_120101_users_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_120101_users_module_insert_role extends \yii\db\Migration
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
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['usersModLevelAdmin', '2', '', time()],
				['usersModLevelModerator', '2', '', time()],
				['/users/admin/*', '2', '', time()],
				['/users/admin/index', '2', '', time()],
				['/users/member/*', '2', '', time()],
				['/users/member/index', '2', '', time()],
				['/users/o/invite/*', '2', '', time()],
				['/users/o/invite/index', '2', '', time()],
				['/users/o/newsletter/*', '2', '', time()],
				['/users/o/newsletter/index', '2', '', time()],
				['/users/o/forgot/*', '2', '', time()],
				['/users/o/forgot/index', '2', '', time()],
				['/users/o/verify/*', '2', '', time()],
				['/users/o/verify/index', '2', '', time()],
				['/users/o/phone/*', '2', '', time()],
				['/users/history/invite/*', '2', '', time()],
				['/users/history/newsletter/*', '2', '', time()],
				['/users/history/login/*', '2', '', time()],
				['/users/history/email/*', '2', '', time()],
				['/users/history/password/*', '2', '', time()],
				['/users/setting/admin/index', '2', '', time()],
				['/users/setting/admin/update', '2', '', time()],
				['/users/setting/admin/delete', '2', '', time()],
				['/users/setting/level/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', 'usersModLevelAdmin'],
				['userModerator', 'usersModLevelModerator'],
				['usersModLevelAdmin', 'usersModLevelModerator'],
				['usersModLevelAdmin', '/users/setting/admin/update'],
				['usersModLevelAdmin', '/users/setting/admin/delete'],
				['usersModLevelAdmin', '/users/setting/level/*'],
				['usersModLevelAdmin', '/users/admin/*'],
				['usersModLevelModerator', '/users/setting/admin/index'],
				['usersModLevelModerator', '/users/member/*'],
				['usersModLevelModerator', '/users/o/invite/*'],
				['usersModLevelModerator', '/users/o/newsletter/*'],
				['usersModLevelModerator', '/users/o/forgot/*'],
				['usersModLevelModerator', '/users/o/verify/*'],
				['usersModLevelModerator', '/users/o/phone/*'],
				['usersModLevelModerator', '/users/history/invite/*'],
				['usersModLevelModerator', '/users/history/newsletter/*'],
				['usersModLevelModerator', '/users/history/login/*'],
				['usersModLevelModerator', '/users/history/email/*'],
				['usersModLevelModerator', '/users/history/password/*'],
			]);
		}
	}
}
