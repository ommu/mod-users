<?php
/**
 * m210909_202933_users_module_create_table_setting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:31 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_202933_users_module_create_table_setting extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_setting';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_TINYINT . '(1) UNSIGNED NOT NULL AUTO_INCREMENT',
				'license' => Schema::TYPE_STRING . '(32) NOT NULL',
				'permission' => Schema::TYPE_TINYINT . '(1) NOT NULL',
				'meta_description' => Schema::TYPE_TEXT . ' NOT NULL',
				'meta_keyword' => Schema::TYPE_TEXT . ' NOT NULL',
				'forgot_diff_type' => Schema::TYPE_STRING . ' NOT NULL COMMENT \'"0=day,1=hour"\'',
				'forgot_difference' => Schema::TYPE_TINYINT . '(2) NOT NULL',
				'verify_diff_type' => Schema::TYPE_STRING . ' NOT NULL COMMENT \'"0=day,1=hour"\'',
				'verify_difference' => Schema::TYPE_TINYINT . '(2) NOT NULL',
				'invite_diff_type' => Schema::TYPE_STRING . ' NOT NULL COMMENT \'"0=day,1=hour"\'',
				'invite_difference' => Schema::TYPE_TINYINT . '(2) NOT NULL',
				'invite_order' => Schema::TYPE_STRING,
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_setting';
		$this->dropTable($tableName);
	}
}
