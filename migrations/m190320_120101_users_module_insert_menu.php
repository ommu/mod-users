<?php
/**
 * m190320_120101_users_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use app\models\Menu;
use mdm\admin\components\Configs;

class m190320_120101_users_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Users', 'users', 'fa-user-circle-o', null, '/#', null, null],
			]);
		}

        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Administrator', 'users', null, Menu::getParentId('Users#users'), '/users/admin/index', 1, null],
				['Member', 'users', null, Menu::getParentId('Users#users'), '/users/member/index', 2, null],
				['Invite, Forgot & Subscribe', 'users', null, Menu::getParentId('Users#users'), '/users/o/invite/index', 3, null],
                ['User Settings', 'users', null, Menu::getParentId('Settings#rbac'), '/users/setting/admin/index', null, null],
                ['Permission Manager', 'rbac', null, Menu::getParentId('Development Tools#rbac'), '/rbac/assignment/index', null, null],
                ['Role Manager', 'rbac', null, Menu::getParentId('Development Tools#rbac'), '/rbac/role/index', null, null],
			]);
		}
	}
}
