<?php
/**
 * m210909_204124_users_module_create_table_user_newsletter_history
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:41 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_204124_users_module_create_table_user_newsletter_history extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_newsletter_history';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger\'',
				'status' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT \'Subscribe,Unsubscribe\'',
				'newsletter_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'updated_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_user_newsletter_history_ibfk_1 FOREIGN KEY ([[newsletter_id]]) REFERENCES ommu_user_newsletter ([[newsletter_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_newsletter_history';
		$this->dropTable($tableName);
	}
}
