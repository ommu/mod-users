<?php
/**
 * m190320_120101_users_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use Yii;
use app\models\Menu;

class m190320_120101_users_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_menus';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Users', 'users', 'fa-user-circle-o', null, '/#', null, null],
			]);
		}

		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Administrator', 'users', null, Menu::getParentId('Users#users'), '/users/admin/index', null, null],
				['Member', 'users', null, Menu::getParentId('Users#users'), '/users/member/index', null, null],
				['Invite, Forgot & Subscribe', 'users', null, Menu::getParentId('Users#users'), '/users/o/invite/index', null, null],
				['User Settings', 'users', null, Menu::getParentId('Settings#rbac'), '/users/setting/admin/index', null, null],
				['Permission Manager', 'rbac', null, Menu::getParentId('Users#users'), '/rbac/assignment/index', null, null],
			]);
		}
	}
}
