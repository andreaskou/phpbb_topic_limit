<?php

/**
* This file is part of the phpBB Forum extension package (User Topic Limit).
*
* @copyright (c) 2016 by Andreas Kourtidis
* @license   GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the CREDITS.txt file.
*/

namespace andreask\usertopiclimit\migrations;

use phpbb\db\migration\migration;

class add_modules extends migration
{

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v316');
	}

	public function update_data()
	{
		return array(
			// add module
			array('module.add', array(
				'acp',
				'ACP_MESSAGES',
				array(
					'module_basename' => '\andreask\usertopiclimit\acp\usertopiclimit_module',
					'modes' => array('settings'),
				),
			)),
		);
	}
}
