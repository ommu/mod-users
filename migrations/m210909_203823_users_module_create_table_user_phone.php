<?php
/**
 * m210909_203823_users_module_create_table_user_phone
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 9 September 2021, 20:39 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use yii\db\Schema;

class m210909_203823_users_module_create_table_user_phone extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_phones';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'phone_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'verified' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Verified,Unverified\'',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'phone_number' => Schema::TYPE_STRING . '(20) NOT NULL',
				'verified_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'_emp_data_id' => Schema::TYPE_INTEGER . '(11)',
				'PRIMARY KEY ([[phone_id]])',
				'CONSTRAINT ommu_user_phones_ibfk_1 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

            $this->createIndex(
                'phone_number',
                $tableName,
                ['phone_number']
            );

            $this->createIndex(
                'userId_PhoneNumber',
                $tableName,
                ['user_id', 'phone_number']
            );
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_user_phones';
		$this->dropTable($tableName);
	}
}
