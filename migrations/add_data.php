<?php

/**
* This file is part of the phpBB Forum extension package
* UTL (User Topic Limit).
*
* @copyright (c) 2016 by Andreas Kourtidis
* @license   GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the CREDITS.txt file.
*/

namespace andreask\usertopiclimit\migrations;

use phpbb\db\migration\migration;

/**
 * Add needed data to database of phpbb
 */

class add_data extends migration
{

	// public function update_data()
	// {
	// 	return array(
	// 		array('config.add', array('andreask_ium_enable', 0)),
	// 		array('config.add', array('andreask_ium_interval', 30)),
	// 		array('config.add', array('andreask_ium_top_user_threads', 0)),
	// 		array('config.add', array('andreask_ium_top_user_threads_count', 5)),
	// 		array('config.add', array('andreask_ium_top_forum_threads', 0)),
	// 		array('config.add', array('andreask_ium_top_forum_threads_count', 5)),
	// 		array('config.add', array('andreask_ium_email_limit', 250)),
	// 		array('config.add', array('andreask_ium_self_delete', 0)),
	// 		// 0.9.0
	// 		array('config.add', array('andreask_ium_version',   '1.1.0')),
	// 		array('config.add', array('andreask_ium_keep_posts',    1)),
	// 		array('config.add', array('andreask_ium_approve_del',   1)),
	// 		// 0.9.1
	// 		array('config.add', array('andreask_ium_auto_del',		0)),
	// 		array('config.add', array('andreask_ium_auto_del_days', 7)),
	// 		// 0.9.6
	// 		array('config_text.add', array('andreask_ium_ignore_forum',	'[]')),
	// 		// 0.9.9
	// 		array('config_text.add', array('andreask_ium_ignored_groups', '[]')),
	// 		// cron config
	// 		array('config.add', array('send_reminder_last_gc', 0, true)),
	// 		array('config.add', array('send_reminder_gc', (60 * 60 * 24))),
	// 		array('config.add', array('reminder_limit', 250)),
	// 		);
	// }

  static public function depends_on()
  {
    return array('\phpbb\db\migration\data\v310\gold');
  }

	public function update_schema()
  {
    return array(
        'add_tables' => array(
            $this->table_prefix . 'andreask_utl' => array(
                'COLUMNS'   => array(
                    'utl_rule_id'					=>  array('UINT', NULL, 'auto_increment'),
                    'utl_rule_name'					=>  array('VCHAR:255', NULL),

                    'utl_rule_amount_of_days'		=>  array('UINT:3', NULL),
                    'utl_rule_amount_of_days_opt'	=>  array('UINT:1', NULL),

                    'utl_rule_amount_of_posts'		=>  array('UINT:10', NULL),
                    'utl_rule_amount_of_posts_opt'	=>  array('UINT:1', NULL),

                    'utl_rule_max_posts'            =>  array('UINT:10', NULL),
                    'utl_rule_max_posts_opt'        =>  array('UINT:1', NULL),

                    'utl_rule_forum_ids'			=>  array('TEXT', NULL),
                    'utl_rule_active'               =>  array('UINT:1', NULL), //Just added if something wrong with the installatiion check this line first!

                    'utl_rule_created_date'         => array('TIMESTAMP', NULL),
                    'utl_rule_updated_date'         => array('TIMESTAMP', NULL),
                    ),
                    'PRIMARY_KEY'   => 'utl_rule_id',
                ),
            ),
        );
  }

  public function revert_schema()
  {
	  return array(
		  'drop_tables' => array(
			  $this->table_prefix . 'andreask_utl',
		  ),
  		);
	}

}
