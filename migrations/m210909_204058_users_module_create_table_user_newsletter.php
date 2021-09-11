<?php
/**
 * m210909_204058_users_module_create_table_user_newsletter
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:41 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use Yii;
use yii\db\Schema;

class m210909_204058_users_module_create_table_user_newsletter extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_newsletter';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'newsletter_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'status' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'Subscribe,Unsubscribe\'',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'trigger[insert,update => in after insert users]\'',
				'email' => Schema::TYPE_STRING . '(64) NOT NULL',
				'reference_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'user\'',
				'subscribe_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'user\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'updated_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'PRIMARY KEY ([[newsletter_id]])',
				'CONSTRAINT ommu_user_newsletter_ibfk_1 FOREIGN KEY ([[reference_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_user_newsletter_ibfk_2 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'email',
                $tableName,
                ['email']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_newsletter';
		$this->dropTable($tableName);
	}
}
