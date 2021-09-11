<?php
/**
 * m210909_203945_users_module_create_table_invite
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:39 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use Yii;
use yii\db\Schema;

class m210909_203945_users_module_create_table_invite extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_invites';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'newsletter_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'level_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'displayname' => Schema::TYPE_STRING . '(64) NOT NULL',
				'code' => Schema::TYPE_STRING . '(16) NOT NULL',
				'invites' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'1\'',
				'inviter_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'user\'',
				'invite_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'invite_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_user_invites_ibfk_1 FOREIGN KEY ([[newsletter_id]]) REFERENCES ommu_user_newsletter ([[newsletter_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_user_invites_ibfk_2 FOREIGN KEY ([[inviter_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_user_invites_ibfk_3 FOREIGN KEY ([[level_id]]) REFERENCES ommu_user_level ([[level_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
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
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_invites';
		$this->dropTable($tableName);
	}
}
