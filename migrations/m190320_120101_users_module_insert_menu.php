<?php
/**
 * m190320_120101_users_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
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
				['Users', 'users', null, null, '/#', null, null],
			]);
		}

		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Datas', 'users', null, Menu::getParentId('Users#users'), '/#', null, null],
			]);
		}

		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Administrator', 'users', null, Menu::getParentId('Users#users'), '/users/admin/index', null, null],
				['Member', 'users', null, Menu::getParentId('Users#users'), '/users/member/index', null, null],
				['Invites', 'users', null, Menu::getParentId('Datas#users'), '/users/o/invite/index', null, null],
				['Subscribers', 'users', null, Menu::getParentId('Datas#users'), '/users/o/newsletter/index', null, null],
				['Forgot Password', 'users', null, Menu::getParentId('Datas#users'), '/users/o/forgot/index', null, null],
				['Verify Email', 'users', null, Menu::getParentId('Datas#users'), '/users/o/verify/index', null, null],
				['User Settings', 'users', null, Menu::getParentId('Settings#rbac'), '/users/setting/admin/index', null, null],
			]);
		}
	}
}
