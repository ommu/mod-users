<?php
/**
 * m210909_204511_users_module_create_table_user_history_login
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:45 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_204511_users_module_create_table_user_history_login extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_history_login';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger\'',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'lastlogin_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
				'lastlogin_ip' => Schema::TYPE_STRING . '(32) NOT NULL',
				'lastlogin_from' => Schema::TYPE_STRING . '(32) NOT NULL',
				'_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'_user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_user_history_login_ibfk_1 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'lastlogin_date',
                $tableName,
                ['lastlogin_date']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_history_login';
		$this->dropTable($tableName);
	}
}
