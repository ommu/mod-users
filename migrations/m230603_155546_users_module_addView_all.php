<?php
/**
 * m230603_155546_users_module_addView_all
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

class m230603_155546_users_module_addView_all extends \yii\db\Migration
{
	public function up()
	{
		// alter view _user_setting
		$alterViewUserSetting = <<< SQL
CREATE VIEW `_user_setting` AS
select
`a`.`id` AS `id`,
case when `a`.`forgot_diff_type` = '0' then `a`.`forgot_difference` * 24 else `a`.`forgot_difference` end AS `forgot_difference_hours`,
case when `a`.`verify_diff_type` = '0' then `a`.`verify_difference` * 24 else `a`.`verify_difference` end AS `verify_difference_hours`,
case when `a`.`invite_diff_type` = '0' then `a`.`invite_difference` * 24 else `a`.`invite_difference` end AS `invite_difference_hours`
from `ommu_user_setting` `a`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_setting`');
		$this->execute($alterViewUserSetting);

		// alter view _user_level
		$alterViewUserLevel = <<< SQL
CREATE VIEW `_user_level` AS
select
  `a`.`level_id` AS `level_id`,
  sum(case when `b`.`enabled` = '1' and `b`.`verified` = '1' then 1 else 0 end) AS `user_active`,
  sum(case when `b`.`enabled` = '0' and `b`.`verified` = '1' then 1 else 0 end) AS `user_pending`,
  sum(case when `b`.`enabled` = '1' and `b`.`verified` = '0' then 1 else 0 end) AS `user_noverified`,
  sum(case when `b`.`enabled` = '2' or `b`.`enabled` = '0' and `b`.`verified` = '0' then 1 else 0 end) AS `user_blocked`,
  count(`b`.`user_id`) AS `user_all`
from (`ommu_user_level` `a`
   left join `ommu_users` `b`
     on (`a`.`level_id` = `b`.`level_id`))
group by `a`.`level_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_level`');
		$this->execute($alterViewUserLevel);

		// alter view _user_newsletter
		$alterViewUserNewsletter = <<< SQL
CREATE VIEW `_user_newsletter` AS
select
`a`.`newsletter_id` AS `newsletter_id`,case when `a`.`user_id` <> '0' then 1 else 0 end AS `register`,
case when `b`.`publish` = '1' and `c`.`enabled` = '1' and `c`.`level_id` in ('1','2') then 'admin' else 'user' end AS `invite_by`,
sum(case when `b`.`publish` = '1' then `b`.`invites` else 0 end) AS `invites`,sum(`b`.`invites`) AS `invite_all`,
sum(case when `b`.`publish` = '1' and `b`.`inviter_id` is not null then 1 else 0 end) AS `invite_users`,
count(`b`.`id`) AS `invite_user_all`,
case when `b`.`publish` = '1' then min(`b`.`invite_date`) end AS `first_invite_date`,
case when `b`.`publish` = '1' then min(`b`.`inviter_id`) end AS `first_invite_user_id`,
case when `b`.`publish` = '1' then max(`b`.`invite_date`) end AS `last_invite_date`,
case when `b`.`publish` = '1' then max(`b`.`inviter_id`) end AS `last_invite_user_id`
from ((`ommu_user_newsletter` `a`
left join `ommu_user_invites` `b` on(`a`.`newsletter_id` = `b`.`newsletter_id`))
left join `ommu_users` `c` on(`b`.`inviter_id` = `c`.`user_id`))
group by `a`.`newsletter_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_newsletter`');
		$this->execute($alterViewUserNewsletter);

		// alter view _user_invite_history
		$alterViewUserInviteHistory = <<< SQL
CREATE VIEW `_user_invite_history` AS
select
`a`.`id` AS `id`,
case when `b`.`publish` = '0' or `b`.`publish` = '1' and `a`.`expired_date` <= current_timestamp() then 1 else 0 end AS `expired`,
case when `b`.`publish` = '1' and `a`.`expired_date` >= current_timestamp() then timestampdiff(DAY,current_timestamp(),`a`.`expired_date`) else 0 end AS `verify_day_left`,
case when `b`.`publish` = '1' and `a`.`expired_date` >= current_timestamp() then timestampdiff(HOUR,current_timestamp(),`a`.`expired_date`) else 0 end AS `verify_hour_left`
from (`ommu_user_invite_history` `a`
left join `ommu_user_invites` `b` on(`a`.`invite_id` = `b`.`id`))
group by `a`.`id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_invite_history`');
		$this->execute($alterViewUserInviteHistory);

		// alter view _user_statistic_history_email
		$alterViewUserStatisticHistoryEmail = <<< SQL
CREATE VIEW `_user_statistic_history_email` AS
select
  `a`.`user_id` AS `user_id`,
  count(`a`.`user_id`) AS `emails`,
  max(`a`.`update_date`) AS `email_lastchange_date`
from `ommu_user_history_email` `a`
group by `a`.`user_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_statistic_history_email`');
		$this->execute($alterViewUserStatisticHistoryEmail);

		// alter view _user_statistic_history_login
		$alterViewUserStatisticHistoryLogin = <<< SQL
CREATE VIEW `_user_statistic_history_login` AS
select
  `a`.`user_id` AS `user_id`,
  count(`a`.`user_id`) AS `logins`,
  max(`a`.`lastlogin_date`) AS `lastlogin_date`,
  max(`a`.`lastlogin_from`) AS `lastlogin_from`
from `ommu_user_history_login` `a`
group by `a`.`user_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_statistic_history_login`');
		$this->execute($alterViewUserStatisticHistoryLogin);

		// alter view _user_statistic_history_password
		$alterViewUserStatisticHistoryPassword = <<< SQL
CREATE VIEW `_user_statistic_history_password` AS
select
  `a`.`user_id` AS `user_id`,
  count(`a`.`user_id`) AS `passwords`,
  max(`a`.`update_date`) AS `password_lastchange_date`
from `ommu_user_history_password` `a`
group by `a`.`user_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_statistic_history_password`');
		$this->execute($alterViewUserStatisticHistoryPassword);

		// alter view _user_history
		$alterViewUserHistory = <<< SQL
CREATE VIEW `_user_history` AS
select
`a`.`user_id` AS `user_id`,
`b`.`emails` AS `emails`,
`b`.`email_lastchange_date` AS `email_lastchange_date`,
case when `b`.`email_lastchange_date` is not null then timestampdiff(DAY,`b`.`email_lastchange_date`,current_timestamp()) else 0 end AS `email_lastchange_days`,
case when `b`.`email_lastchange_date` is not null then timestampdiff(HOUR,`b`.`email_lastchange_date`,current_timestamp()) else 0 end AS `email_lastchange_hours`,
`c`.`passwords` AS `passwords`,`c`.`password_lastchange_date` AS `password_lastchange_date`,
case when `c`.`password_lastchange_date` is not null then timestampdiff(DAY,`c`.`password_lastchange_date`,current_timestamp()) else 0 end AS `password_lastchange_days`,
case when `c`.`password_lastchange_date` is not null then timestampdiff(HOUR,`c`.`password_lastchange_date`,current_timestamp()) else 0 end AS `password_lastchange_hours`,
`d`.`logins` AS `logins`,
`d`.`lastlogin_date` AS `lastlogin_date`,
case when `d`.`lastlogin_date` is not null then timestampdiff(DAY,`d`.`lastlogin_date`,current_timestamp()) else 0 end AS `lastlogin_days`,
case when `d`.`lastlogin_date` is not null then timestampdiff(HOUR,`d`.`lastlogin_date`,current_timestamp()) else 0 end AS `lastlogin_hours`,
`d`.`lastlogin_from` AS `lastlogin_from`
from (((`ommu_users` `a`
left join `_user_statistic_history_email` `b` on(`a`.`user_id` = `b`.`user_id`))
left join `_user_statistic_history_password` `c` on(`a`.`user_id` = `c`.`user_id`))
left join `_user_statistic_history_login` `d` on(`a`.`user_id` = `d`.`user_id`));
SQL;
		$this->execute('DROP VIEW IF EXISTS `_user_history`');
		$this->execute($alterViewUserHistory);

		// alter view _users
		$alterViewUsers = <<< SQL
CREATE VIEW `_users` AS
select
  `a`.`user_id` AS `user_id`,
  md5(concat(`a`.`salt`,`a`.`creation_date`)) AS `token_key`,
  md5(concat(`a`.`salt`,`a`.`password`)) AS `token_password`,
  md5(concat(md5(concat(`a`.`salt`,`a`.`password`)),`a`.`lastlogin_date`)) AS `token_oauth`
from `ommu_users` `a`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_users`');
		$this->execute($alterViewUsers);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_user_setting`');
		$this->execute('DROP VIEW IF EXISTS `_user_level`');
		$this->execute('DROP VIEW IF EXISTS `_user_newsletter`');
		$this->execute('DROP VIEW IF EXISTS `_user_invite_history`');
		$this->execute('DROP VIEW IF EXISTS `_user_statistic_history_email`');
		$this->execute('DROP VIEW IF EXISTS `_user_statistic_history_login`');
		$this->execute('DROP VIEW IF EXISTS `_user_statistic_history_password`');
		$this->execute('DROP VIEW IF EXISTS `_user_history`');
		$this->execute('DROP VIEW IF EXISTS `_users`');
	}
}
