<?php
/**
 * m210909_203332_users_module_create_table_user_option
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:33 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_203332_users_module_create_table_user_option extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_option';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'option_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger\'',
				'invite_limit' => Schema::TYPE_SMALLINT . '(5) NOT NULL DEFAULT \'10\'',
				'invite_success' => Schema::TYPE_SMALLINT . '(5) NOT NULL DEFAULT \'0\'',
				'signup_from' => Schema::TYPE_TEXT . ' NOT NULL',
				'PRIMARY KEY ([[option_id]])',
				'CONSTRAINT ommu_user_option_ibfk_1 FOREIGN KEY ([[option_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_option';
		$this->dropTable($tableName);
	}
}
