<?php
/**
 * m230603_155557_users_module_addStoreProsedure_all
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 15:59 WIB
 * @link https://github.com/ommu/mod-users
 *
 */

use Yii;
use yii\db\Schema;

class m230603_155557_users_module_addStoreProsedure_all extends \yii\db\Migration
{
	public function up()
	{
		// alter sp userGetlevelIdWithUserId
		$alterProsedureUserGetlevelIdWithUserId = <<< SQL
CREATE PROCEDURE `userGetlevelIdWithUserId`(IN `user_id_sp` INT, OUT `level_id_sp` TINYINT)
BEGIN
	SELECT `level_id` INTO level_id_sp FROM `ommu_users` WHERE `user_id`=user_id_sp;
END;
SQL;
        $this->execute('DROP PROCEDURE IF EXISTS `userGetlevelIdWithUserId`');
		$this->execute($alterProsedureUserGetlevelIdWithUserId);

		// alter sp userGetNewsletterIdWithEmail
		$alterProsedureUserGetNewsletterIdWithEmail = <<< SQL
CREATE PROCEDURE `userGetNewsletterIdWithEmail`(IN `email_sp` TEXT, OUT `newsletter_id_sp` INT)
BEGIN
	SELECT `newsletter_id` INTO newsletter_id_sp FROM `ommu_user_newsletter` WHERE `email`=email_sp;
END;
SQL;
        $this->execute('DROP PROCEDURE IF EXISTS `userGetNewsletterIdWithEmail`');
		$this->execute($alterProsedureUserGetNewsletterIdWithEmail);

		// alter sp userGetSetting
		$alterProsedureUserGetSetting = <<< SQL
CREATE PROCEDURE `userGetSetting`(OUT `forgot_difference_hours_sp` INT, OUT `verify_difference_hours_sp` INT, OUT `invite_difference_hours_sp` INT)
BEGIN
	SELECT `b`.`forgot_difference_hours` , `b`.`verify_difference_hours` , `b`.`invite_difference_hours` 
	INTO `forgot_difference_hours_sp`, `verify_difference_hours_sp`, `invite_difference_hours_sp`
	FROM `ommu_user_setting` AS `a` 
	LEFT JOIN `_user_setting` AS `b` ON `a`.`id`=`b`.`id`
	WHERE `a`.`id`='1';
END;
SQL;
        $this->execute('DROP PROCEDURE IF EXISTS `userGetSetting`');
		$this->execute($alterProsedureUserGetSetting);
	}

	public function down()
	{
        $this->execute('DROP PROCEDURE IF EXISTS `userGetlevelIdWithUserId`');
        $this->execute('DROP PROCEDURE IF EXISTS `userGetNewsletterIdWithEmail`');
        $this->execute('DROP PROCEDURE IF EXISTS `userGetSetting`');
	}
}
