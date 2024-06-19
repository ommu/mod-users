<?php
/**
 * m210909_204010_users_module_create_table_invite_history
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:40 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_204010_users_module_create_table_invite_history extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_invite_history';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger\'',
				'invite_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'code' => Schema::TYPE_STRING . '(16) NOT NULL',
				'invite_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
				'invite_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'expired_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_user_invite_history_ibfk_1 FOREIGN KEY ([[invite_id]]) REFERENCES ommu_user_invites ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'code',
                $tableName,
                ['code']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_invite_history';
		$this->dropTable($tableName);
	}
}
