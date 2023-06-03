<?php
/**
 * m230603_155615_users_module_addTrigger_all
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

class m230603_155615_users_module_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
		// alter trigger userBeforeInsertLevel
		$alterTriggerUserBeforeInsertLevel = <<< SQL
CREATE
    TRIGGER `userBeforeInsertLevel` BEFORE INSERT ON `ommu_user_level` 
    FOR EACH ROW BEGIN
	IF (NEW.default = 1) THEN
		SET NEW.signup = 1;
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertLevel`');
		$this->execute($alterTriggerUserBeforeInsertLevel);

		// alter trigger userBeforeUpdateLevel
		$alterTriggerUserBeforeUpdateLevel = <<< SQL
CREATE
    TRIGGER `userBeforeUpdateLevel` BEFORE UPDATE ON `ommu_user_level` 
    FOR EACH ROW BEGIN
	IF (NEW.default <> OLD.default AND NEW.default = 1) THEN
		SET NEW.signup = 1;
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateLevel`');
		$this->execute($alterTriggerUserBeforeUpdateLevel);

		// alter trigger userAfterDeleteLevel
		$alterTriggerUserAfterDeleteLevel = <<< SQL
CREATE
    TRIGGER `userAfterDeleteLevel` AFTER DELETE ON `ommu_user_level` 
    FOR EACH ROW BEGIN	
	/*
	DELETE FROM `source_message` WHERE `id`=OLD.name;
	DELETE FROM `source_message` WHERE `id`=OLD.desc;
	*/
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.name;
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.desc;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterDeleteLevel`');
		$this->execute($alterTriggerUserAfterDeleteLevel);

		// alter trigger userBeforeInsert
		$alterTriggerUserBeforeInsert = <<< SQL
CREATE
    TRIGGER `userBeforeInsert` BEFORE INSERT ON `ommu_users` 
    FOR EACH ROW BEGIN
	DECLARE language_id_tr TINYINT;
	
	/* Language */
	IF (NEW.language_id IS NULL) THEN
		CALL coreGetLanguageDefault(language_id_tr);
		SET NEW.language_id = language_id_tr;
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsert`');
		$this->execute($alterTriggerUserBeforeInsert);

		// alter trigger userAfterInsert
		$alterTriggerUserAfterInsert = <<< SQL
CREATE
    TRIGGER `userAfterInsert` AFTER INSERT ON `ommu_users` 
    FOR EACH ROW BEGIN
	DECLARE signup_numgiven_tr TINYINT;
	DECLARE option_id_tr INT;
	DECLARE subscribe_id_tr INT;
	DECLARE newsletter_id_tr INT;
	
	DECLARE member_id_tr INT;
	DECLARE buddy_member_id_tr INT;
	
	CALL coreGetSetting(signup_numgiven_tr);
	
	/* User Option */
	SELECT `option_id` INTO option_id_tr FROM `ommu_user_option` WHERE `option_id`=NEW.user_id;
	IF (option_id_tr IS NULL) THEN
		INSERT `ommu_user_option` (`option_id`, `invite_limit`)
		VALUE (NEW.user_id, signup_numgiven_tr);
	END IF;
	
	/* Newsletter */	
	IF (NEW.modified_id = '0') THEN
		SET subscribe_id_tr = NEW.user_id;
	ELSE
		SET subscribe_id_tr = NEW.modified_id;
	END IF;
	
	CALL userGetNewsletterIdWithEmail(NEW.email, newsletter_id_tr);
	IF (newsletter_id_tr IS NOT NULL) THEN
		UPDATE `ommu_user_newsletter` SET `status`='1', `user_id`=NEW.user_id, `updated_ip`=NEW.creation_ip WHERE `newsletter_id`=newsletter_id_tr;
	ELSE
		INSERT `ommu_user_newsletter` (`status`, `user_id`, `email`, `creation_date`, `subscribe_id`, `updated_ip`)
		VALUE ('1', NEW.user_id, NEW.email, NEW.creation_date, subscribe_id_tr, NEW.creation_ip);
	END IF;
	
	/* History Email, Username, Displayname and Password */
	IF (NEW.email <> '') THEN
		INSERT `ommu_user_history_email` (`user_id`, `email`, `update_date`)
		VALUE (NEW.user_id, NEW.email, NEW.creation_date);
	END IF;
	IF (NEW.username <> '') THEN
		INSERT `ommu_user_history_username` (`user_id`, `username`, `update_date`)
		VALUE (NEW.user_id, NEW.username, NEW.creation_date);
	END IF;
	IF (NEW.displayname <> '') THEN
		INSERT `ommu_user_history_displayname` (`user_id`, `displayname`, `update_date`)
		VALUE (NEW.user_id, NEW.displayname, NEW.creation_date);
	END IF;
	IF (NEW.password <> '') THEN
		INSERT `ommu_user_history_password` (`user_id`, `password`, `update_date`)
		VALUE (NEW.user_id, NEW.password, NEW.creation_date);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterInsert`');
		$this->execute($alterTriggerUserAfterInsert);

		// alter trigger userAfterUpdate
		$alterTriggerUserAfterUpdate = <<< SQL
CREATE
    TRIGGER `userAfterUpdate` AFTER UPDATE ON `ommu_users` 
    FOR EACH ROW BEGIN
	DECLARE newsletter_status_tr INT;
	DECLARE update_date_tr DATETIME;
	DECLARE newsletter_id_tr INT;
	DECLARE old_newsletter_id_tr INT;
	
	/* Newsletter */
	IF (NEW.deactivate <> OLD.deactivate OR NEW.enabled <> OLD.enabled) THEN
		IF (NEW.deactivate = 1 OR NEW.enabled = 0) THEN
			SET newsletter_status_tr = '0';
		ELSE
			SET newsletter_status_tr = '1';
		END IF;
		UPDATE `ommu_user_newsletter` SET `status`=newsletter_status_tr, `updated_ip`=NEW.update_ip WHERE `user_id`=NEW.user_id AND `email`=NEW.email;
	END IF;
	
	/* History Email, Username, Displayname, Password and Login + Newsletter */
	IF (NEW.modified_date <> OLD.modified_date OR NEW.update_date <> OLD.update_date) THEN
		IF (DATE(NEW.update_date) >= DATE(NEW.modified_date)) THEN
			SET update_date_tr = NEW.update_date;
		ELSE
			SET update_date_tr = NEW.modified_date;
		END IF;
	END IF;
	IF (NEW.email <> OLD.email) THEN
		INSERT `ommu_user_history_email` (`user_id`, `email`, `update_date`)
		VALUE (NEW.user_id, NEW.email, update_date_tr);
		
		/* Newsletter */
		CALL userGetNewsletterIdWithEmail(NEW.email, newsletter_id_tr);
		CALL userGetNewsletterIdWithEmail(OLD.email, old_newsletter_id_tr);
		IF (newsletter_id_tr IS NOT NULL) THEN
			UPDATE `ommu_user_newsletter` SET `status`='1', `user_id`=NEW.user_id, `updated_ip`=NEW.update_ip WHERE `newsletter_id`=newsletter_id_tr;
		ELSE
			INSERT `ommu_user_newsletter` (`status`, `user_id`, `email`, `creation_date`, `subscribe_id`, `updated_ip`)
			VALUE ('1', NEW.user_id, NEW.email, update_date_tr, NEW.user_id, NEW.update_ip);
		END IF;
		IF (old_newsletter_id_tr IS NOT NULL) THEN
			UPDATE `ommu_user_newsletter` SET `status`='0', `user_id`=NEW.user_id, `updated_ip`=NEW.update_ip WHERE `newsletter_id`=old_newsletter_id_tr;
		END IF;
	END IF;	
	IF (NEW.username <> OLD.username) THEN
		INSERT `ommu_user_history_username` (`user_id`, `username`, `update_date`)
		VALUE (NEW.user_id, NEW.username, update_date_tr);
	END IF;
	IF (NEW.displayname <> OLD.displayname) THEN
		INSERT `ommu_user_history_displayname` (`user_id`, `displayname`, `update_date`)
		VALUE (NEW.user_id, NEW.displayname, update_date_tr);
	END IF;
	IF (NEW.password <> OLD.password) THEN
		INSERT `ommu_user_history_password` (`user_id`, `password`, `update_date`)
		VALUE (NEW.user_id, NEW.password, update_date_tr);
	END IF;
	
	IF (NEW.lastlogin_date <> OLD.lastlogin_date) THEN
		INSERT `ommu_user_history_login` (`user_id`, `lastlogin_date`, `lastlogin_ip`, `lastlogin_from`)
		VALUE (NEW.user_id, NEW.lastlogin_date, NEW.lastlogin_ip, NEW.lastlogin_from);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterUpdate`');
		$this->execute($alterTriggerUserAfterUpdate);

		// alter trigger userAfterDelete
		$alterTriggerUserAfterDelete = <<< SQL
CREATE
    TRIGGER `userAfterDelete` AFTER DELETE ON `ommu_users` 
    FOR EACH ROW BEGIN
	/* Update Newsletter Unsubscribe */
	UPDATE `ommu_user_newsletter` SET `status`='0', `user_id`=null, `updated_date`=NOW() WHERE `user_id`=OLD.user_id;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterDelete`');
		$this->execute($alterTriggerUserAfterDelete);

		// alter trigger userAfterInsertInvites
		$alterTriggerUserAfterInsertInvites = <<< SQL
CREATE
    TRIGGER `userAfterInsertInvites` AFTER INSERT ON `ommu_user_invites` 
    FOR EACH ROW BEGIN
	DECLARE forgot_difference_hours_tr INT;
	DECLARE verify_difference_hours_tr INT;
	DECLARE invite_difference_hours_tr INT;
	DECLARE expired_date_tr DATETIME;
	
	CALL userGetSetting(forgot_difference_hours_tr, verify_difference_hours_tr, invite_difference_hours_tr);
	
	IF (invite_difference_hours_tr IS NOT NULL) THEN
		SET expired_date_tr = ADDDATE(NEW.invite_date, INTERVAL invite_difference_hours_tr HOUR);
	ELSE
		SET expired_date_tr = ADDDATE(NEW.invite_date, INTERVAL '1' DAY);
	END IF;
	
	IF (NEW.publish = 1 AND NEW.invites = 1) THEN
		INSERT `ommu_user_invite_history` (`invite_id`, `code`, `invite_date`, `invite_ip`, `expired_date`)
		VALUE (NEW.id, NEW.code, NEW.invite_date, NEW.invite_ip, expired_date_tr);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterInsertInvites`');
		$this->execute($alterTriggerUserAfterInsertInvites);

		// alter trigger userBeforeUpdateInvites
		$alterTriggerUserBeforeUpdateInvites = <<< SQL
CREATE
    TRIGGER `userBeforeUpdateInvites` BEFORE UPDATE ON `ommu_user_invites` 
    FOR EACH ROW BEGIN
	IF (NEW.invites <> OLD.invites AND NEW.invites > OLD.invites) THEN
		SET NEW.invite_date = NOW();
	END IF;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateInvites`');
		$this->execute($alterTriggerUserBeforeUpdateInvites);

		// alter trigger userAfterUpdateInvites
		$alterTriggerUserAfterUpdateInvites = <<< SQL
CREATE
    TRIGGER `userAfterUpdateInvites` AFTER UPDATE ON `ommu_user_invites` 
    FOR EACH ROW BEGIN
	DECLARE forgot_difference_hours_tr INT;
	DECLARE verify_difference_hours_tr INT;
	DECLARE invite_difference_hours_tr INT;
	DECLARE expired_date_tr DATETIME;
	
	IF (NEW.invite_date <> OLD.invite_date) THEN
		CALL userGetSetting(forgot_difference_hours_tr, verify_difference_hours_tr, invite_difference_hours_tr);
		
		IF (invite_difference_hours_tr IS NOT NULL) THEN
			SET expired_date_tr = ADDDATE(NEW.invite_date, INTERVAL invite_difference_hours_tr HOUR);
		ELSE
			SET expired_date_tr = ADDDATE(NEW.invite_date, INTERVAL '1' DAY);
		END IF;
		
		INSERT `ommu_user_invite_history` (`invite_id`, `code`, `invite_date`, `invite_ip`, `expired_date`)
		VALUE (NEW.id, NEW.code, NEW.invite_date, NEW.invite_ip, expired_date_tr);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterUpdateInvites`');
		$this->execute($alterTriggerUserAfterUpdateInvites);

		// alter trigger userAfterInsertNewsletter
		$alterTriggerUserAfterInsertNewsletter = <<< SQL
CREATE
    TRIGGER `userAfterInsertNewsletter` AFTER INSERT ON `ommu_user_newsletter` 
    FOR EACH ROW BEGIN
	INSERT `ommu_user_newsletter_history` (`status`, `newsletter_id`, `updated_date`, `updated_ip`)
	VALUE (NEW.status, NEW.newsletter_id, NEW.creation_date, NEW.updated_ip);
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterInsertNewsletter`');
		$this->execute($alterTriggerUserAfterInsertNewsletter);

		// alter trigger userBeforeUpdateNewsletter
		$alterTriggerUserBeforeUpdateNewsletter = <<< SQL
CREATE
    TRIGGER `userBeforeUpdateNewsletter` BEFORE UPDATE ON `ommu_user_newsletter` 
    FOR EACH ROW BEGIN
	/* set updated_date after change subscribe status */
	IF (NEW.user_id = OLD.user_id AND NEW.status <> OLD.status) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateNewsletter`');
		$this->execute($alterTriggerUserBeforeUpdateNewsletter);

		// alter trigger userAfterUpdateNewsletter
		$alterTriggerUserAfterUpdateNewsletter = <<< SQL
CREATE
    TRIGGER `userAfterUpdateNewsletter` AFTER UPDATE ON `ommu_user_newsletter` 
    FOR EACH ROW BEGIN	
	/* History Subscribe */	
	IF (NEW.updated_date <> OLD.updated_date) THEN
		INSERT `ommu_user_newsletter_history` (`status`, `newsletter_id`, `updated_date`, `updated_ip`)
		VALUE (NEW.status, NEW.newsletter_id, NEW.updated_date, NEW.updated_ip);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userAfterUpdateNewsletter`');
		$this->execute($alterTriggerUserAfterUpdateNewsletter);

		// alter trigger userBeforeInsertVerify
		$alterTriggerUserBeforeInsertVerify = <<< SQL
CREATE
    TRIGGER `userBeforeInsertVerify` BEFORE INSERT ON `ommu_user_verify` 
    FOR EACH ROW BEGIN
	DECLARE forgot_difference_hours_tr INT;
	DECLARE verify_difference_hours_tr INT;
	DECLARE invite_difference_hours_tr INT;
	DECLARE expired_date_tr DATETIME;
	
	IF (NEW.verify_date IS NULL OR NEW.verify_date = '') THEN
		SET NEW.verify_date = NOW();
	END IF;
	
	CALL userGetSetting(forgot_difference_hours_tr, verify_difference_hours_tr, invite_difference_hours_tr);
	
	IF (verify_difference_hours_tr IS NOT NULL) THEN
		SET expired_date_tr = ADDDATE(NEW.verify_date, INTERVAL verify_difference_hours_tr HOUR);
	ELSE
		SET expired_date_tr = ADDDATE(NEW.verify_date, INTERVAL '1' DAY);
	END IF;
	
	SET NEW.expired_date = expired_date_tr;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertVerify`');
		$this->execute($alterTriggerUserBeforeInsertVerify);

		// alter trigger userBeforeUpdateVerify
		$alterTriggerUserBeforeUpdateVerify = <<< SQL
CREATE
    TRIGGER `userBeforeUpdateVerify` BEFORE UPDATE ON `ommu_user_verify` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish AND NEW.publish <> 1) THEN
		SET NEW.deleted_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateVerify`');
		$this->execute($alterTriggerUserBeforeUpdateVerify);

		// alter trigger userBeforeInsertForgot
		$alterTriggerUserBeforeInsertForgot = <<< SQL
CREATE
    TRIGGER `userBeforeInsertForgot` BEFORE INSERT ON `ommu_user_forgot` 
    FOR EACH ROW BEGIN
	DECLARE forgot_difference_hours_tr INT;
	DECLARE verify_difference_hours_tr INT;
	DECLARE invite_difference_hours_tr INT;
	DECLARE expired_date_tr DATETIME;
	
	IF (NEW.forgot_date IS NULL OR NEW.forgot_date = '') THEN
		SET NEW.forgot_date = NOW();
	END IF;
	
	CALL userGetSetting(forgot_difference_hours_tr, verify_difference_hours_tr, invite_difference_hours_tr);
	
	IF (forgot_difference_hours_tr IS NOT NULL) THEN
		SET expired_date_tr = ADDDATE(NEW.forgot_date, INTERVAL forgot_difference_hours_tr HOUR);
	ELSE
		SET expired_date_tr = ADDDATE(NEW.forgot_date, INTERVAL '1' DAY);
	END IF;
	
	SET NEW.expired_date = expired_date_tr;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertForgot`');
		$this->execute($alterTriggerUserBeforeInsertForgot);

		// alter trigger userBeforeUpdateForgot
		$alterTriggerUserBeforeUpdateForgot = <<< SQL
CREATE
    TRIGGER `userBeforeUpdateForgot` BEFORE UPDATE ON `ommu_user_forgot` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish AND NEW.publish <> 1) THEN
		SET NEW.deleted_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateForgot`');
		$this->execute($alterTriggerUserBeforeUpdateForgot);

		// alter trigger userBeforeInsertPhone
		$alterTriggerUserBeforeInsertPhone = <<< SQL
CREATE
    TRIGGER `userBeforeInsertPhone` BEFORE INSERT ON `ommu_user_phones` 
    FOR EACH ROW BEGIN
	IF (NEW.verified = 1) THEN
		SET NEW.verified_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertPhone`');
		$this->execute($alterTriggerUserBeforeInsertPhone);

		// alter trigger userBeforeUpdatePhone
		$alterTriggerUserBeforeUpdatePhone = <<< SQL
CREATE
    TRIGGER `userBeforeUpdatePhone` BEFORE UPDATE ON `ommu_user_phones` 
    FOR EACH ROW BEGIN
	IF (NEW.verified <> OLD.verified AND NEW.verified = 1) THEN
		SET NEW.verified_date = NOW();
	END IF;
	
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdatePhone`');
		$this->execute($alterTriggerUserBeforeUpdatePhone);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertLevel`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateLevel`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterDeleteLevel`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterUpdate`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterDelete`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterInsertInvites`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateInvites`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterUpdateInvites`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterInsertNewsletter`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateNewsletter`');
        $this->execute('DROP TRIGGER IF EXISTS `userAfterUpdateNewsletter`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertVerify`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateVerify`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertForgot`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdateForgot`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeInsertPhone`');
        $this->execute('DROP TRIGGER IF EXISTS `userBeforeUpdatePhone`');
	}
}
