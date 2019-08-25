<?php

/**
* This file is part of the phpBB Forum extension package
* IUM (Inactive User Manager).
*
* @copyright (c) 2016 by Andreas Kourtidis
* @license   GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the CREDITS.txt file.
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty( $lang) || !is_array($lang) )
{
	$lang = array();
}

$lang = array_merge(
		$lang, array(
	'ACP_USERTOPICLIMIT'		=>	'User topic Limit',
	'ACP_USERTOPICLIMIT_ENABLE'	=>	'Enable User topic Limit',
	'USER_TOPIC_LIMIT_TITLE'	=>	'User topic Limit Settings',
	'ANDREASK_NOTHING_YET'		=>	'There are not rules set yet',
	'ANDREASK_ADD_RULE'			=>	'Add Rule',
//	blah
	'ANDREASK_UTL_NAME'			=>	'Rule name',
	'ANDREASK_UTL_NAME_EXPLAIN'	=>	'Use simple and self explanatory name for the rule',
	'ANDREASK_UTL_DAYS'			=>	'Amount of days.',
	'ANDREASK_UTL_DAYS_EXPLAIN'	=>	'Amount of days.',
	)
);
