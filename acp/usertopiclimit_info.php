<?php
/**
*
* Auto Groups extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/
namespace andreask\usertopiclimit\acp;

class usertopiclimit_info
{
	public function module()
	{
		return array(
			'filename'	=> '\phpbb\autogroups\acp\usertopiclimit_module',
			'title'		=> 'ACP_USERTOPICLIMIT',
			'version'	=>	'0.0.1',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_USERTOPICLIMIT',
					'auth'	=> 'ext_andreask/usertopiclimit && acl_a_board',
					'cat'	=> array('ACP_MESSAGES')),
			),
		);
	}
}
