<?php
/**
 * m210909_203243_users_module_create_table_users
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:32 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_203243_users_module_create_table_users extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_users';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'enabled' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'"0=disable, 1=enable, 2=blocked"\'',
				'verified' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Verified,Unverified\'',
				'level_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'language_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED COMMENT \'trigger[insert]\'',
				'email' => Schema::TYPE_STRING . '(64) NOT NULL',
				'username' => Schema::TYPE_STRING . '(32) NOT NULL',
				'first_name' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'last_name' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'drop\'',
				'displayname' => Schema::TYPE_STRING . '(64) NOT NULL',
				'photos' => Schema::TYPE_TEXT . ' COMMENT \'drop\'',
				'password' => Schema::TYPE_STRING . '(60) NOT NULL',
				'salt' => Schema::TYPE_STRING . '(32) NOT NULL',
				'deactivate' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'search' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'invisible' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'privacy' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'comments' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'lastlogin_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'lastlogin_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'lastlogin_from' => Schema::TYPE_STRING . '(32) NOT NULL',
				'update_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'update_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'auth_key' => Schema::TYPE_TEXT,
				'jwt_claims' => Schema::TYPE_TEXT . ' COMMENT \'serialize\'',
				'_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'_users_group_id' => Schema::TYPE_TINYINT . '(3) UNSIGNED',
				'_status_user' => Schema::TYPE_TINYINT . '(1)',
				'_cp_emp_data_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'_duplicate_id' => Schema::TYPE_INTEGER . '(11)',
				'PRIMARY KEY ([[user_id]])',
				'CONSTRAINT ommu_users_ibfk_1 FOREIGN KEY ([[language_id]]) REFERENCES ommu_core_languages ([[language_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_users_ibfk_2 FOREIGN KEY ([[level_id]]) REFERENCES ommu_user_level ([[level_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'email',
                $tableName,
                ['email']
            );

            $this->createIndex(
                'username',
                $tableName,
                ['username']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_users';
		$this->dropTable($tableName);
	}
}
