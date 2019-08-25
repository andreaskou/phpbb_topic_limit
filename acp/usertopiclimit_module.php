<?php

namespace andreask\usertopiclimit\acp;

class usertopiclimit_module
{
    public $u_action;
    public $page_title;
    private $utl_table;

    public function __construct()
    {
        $this->utl_table = 'phpbb_andreask_utl';
    }

    public function main($id, $mode)
    {
        global $db, $template, $request, $config, $user, $phpbb_container, $phpbb_root_path, $phpEx;

        $request = $phpbb_container->get('request');
        $config_text = $phpbb_container->get('config_text');
        $language = $phpbb_container->get('language');
        include_once ($phpbb_root_path . 'includes/functions_admin.' . $phpEx );

        // Template stuff
        $this->tpl_name = 'usertopiclimit_config';
        $this->page_title = $language->lang('USER_TOPIC_LIMIT_TITLE');
        add_form_key('andreask_usertopiclimit_settings');

        $action = $request->variable('action','');

        $forum_ids = false;
        if ($action == 'edit')
        {
        }

        switch ($action) {

            case 'add':
                $this->page_title = $language->lang('ANDREASK_ADD_RULE');
                $template->assign_vars(
                    array(
                        'S_UTL_ADD'         =>  true,
                        'S_UTL_MODE'        =>  $action,
                        'U_ACTION'          =>  $this->u_action,
                        'S_FORUM_MULTIPLE'                      =>  true,
                        'S_FORUM_OPTIONS'   =>  make_forum_select($forum_ids, false, true, true, true),
                    ));

                break;

            case 'edit':
                $this->page_title = $language->lang('ANDREASK_EDIT_RULE');
                $rule_array = json_decode($this->get_existing_rules(), true);
                $edit_rule = (int) $request->variable('topic_limit_rule_id', '');
                $forum_ids = $request->variable('forum_id', array(0));
                $rule = $rule_array[$edit_rule];
                $forum_ids = $rule['forum_ids'];

                $template->assign_vars(
                    array(
                        'U_ACTION'                              =>  $this->u_action,
                        'S_UTL_EDIT'                            =>  true,
                        'S_UTL_MODE'                            =>  $action,
                        'S_SELECT_FORUM'                        =>  true,
                        'S_FORUM_MULTIPLE'                      =>  true,
                        'S_RULE_ID'                             =>  $edit_rule,
                        'S_FORUM_OPTIONS'                       =>  make_forum_select($forum_ids, false, true, true, true),
                        'ANDREASK_UTL_RULE_NAME'                =>  $rule['andreask_utl_name'],
                        'ANDREASK_UTL_RULE_DAYS'                =>  $rule['andreask_utl_days'],
                        'ANDREASK_UTL_RULE_DAYS_OPTION'         =>  $rule['andreask_utl_days_option'],
                        'ANDREASK_UTL_RULES_POSTS'              =>  $rule['andreask_utl_posts'],
                        'ANDREASK_UTL_RULES_POSTS_OPTION'       =>  $rule['andreask_utl_posts_option'],
                        'ANDREASK_UTL_RULES_MAX_POSTS'          =>  $rule['andreask_utl_max_posts'],
                        'ANDREASK_UTL_RULES_MAX_POSTS_OPTION'   =>  $rule['andreask_utl_max_posts_option'],

                    ));
                break;

            default:
                break;
        }

        $json_rules = $this->get_existing_rules();
        $json_rules = json_decode($json_rules, true);

        if ($request->is_set_post('submit'))
        {

            if (!check_form_key('andreask_usertopiclimit_settings'))
            {
                 trigger_error('FORM_INVALID');
            }
            $rule_array = [     'utl_rule_name'             => $request->variable('andreask_utl_name', ''),
                                'utl_rule_amount_of_days'   => (int) $request->variable('andreask_utl_days', 0),         'utl_rule_amount_of_days_opt'  => (int) $request->variable('andreask_utl_days_option', ''),
                                'utl_rule_amount_of_posts'  => (int) $request->variable('andreask_utl_posts', 0),        'utl_rule_amount_of_posts_opt' => (int) $request->variable('andreask_utl_posts_option', ''),
                                'utl_rule_max_posts'        => (int) $request->variable('andreask_utl_max_posts', 0),    'utl_rule_max_posts_opt'       => (int) $request->variable('andreask_utl_max_posts_option', ''),
                                'utl_rule_forum_ids'             => json_encode($request->variable('forum_id', array(0))),
            ];

            if ($request->variable('action', '') == 'add')
            {
                $current_timestamp = time();
                $rule_array = array_merge($rule_array, array('utl_rule_created_date' => (int) $current_timestamp));
                // add new rules
                $sql = 'INSERT INTO ' . $this->utl_table . ' ' . $db->sql_build_array('INSERT', $rule_array);
                $db->sql_query($sql);
            }
            if ($request->variable('S_UTL_MODE', '') == 'edit')
            {
                $this->proccess_rules($action, $new_rules, (int) $request->variable('rule_id', ''));
                // update rule
            }
            // $db->sql_query($sql);

            trigger_error($language->lang('ACP_DEMO_SETTING_SAVED') . adm_back_link($this->u_action));
        }

        $sql = 'SELECT * from ' . $this->utl_table;
        $list_rules = $db->sql_query($sql);
        if($list_rules)
        {

            $rules = $db->sql_fetchrowset($list_rules);
            $db->sql_freeresult($list_rules);

            foreach ($rules as $rule)
            {
                $template->assign_block_vars('andreask_utl_rules', $rule);
                $forum_ids = json_decode($rule['utl_rule_forum_ids'], true);
                $sql = 'SELECT forum_name FROM ' . FORUMS_TABLE . ' WHERE ' . $db->sql_in_set('forum_id', $forum_ids);
                $names = $db->sql_query($sql);
                $forum_names = $db->sql_fetchrowset($names);
                foreach ($forum_names as $forum_name)
                {
                    
                    $fn[] = $forum_name['forum_name'];
                    $template->assign_block_vars('andreask_utl_rules.utl_forum_names', $fn);
                }
                unset($fn);
                unset($forum_names);
            }
            // while ($rule = $db->sql_fetchrow($list_rules))
            // {
            //     $forums = json_decode($rule['utl_rule_forum_ids'], true);
            //     $sql = 'SELECT forum_name FROM ' . FORUMS_TABLE . ' WHERE ' . $db->sql_in_set('forum_id', $forums);
            //     $res = $db->sql_query($sql);
            //     $forum_names = $db->sql_fetchrowset($res);
            //     foreach ($forum_names as $name)
            //     {
            //         $clean[] = $name['forum_name'];
            //     }
            //     unset($clean);
            //     $db->sql_freeresult($res);
            //     $template->assign_block_vars('andreask_utl_rules', $rule);
            // }
            // $db->sql_freeresult($list_rules);
        }
    }


    /**
     * Reuturns already stored rules.
     * @return array Already stored rules if rules do not exist should return 0 or Null
     */

    private function get_existing_rules($rule_id = false)
    {
        global $phpbb_container, $db;
        $config_text = $phpbb_container->get('config_text');
        $rules = $config_text->get('andreask_userlimittopic_rule');

        if ($rule_id)
        {
            $rules = $existing_rules[$id];
            $sql =  'SELECT utl_rule_id, utl_rule_name, utl_rule_amount_of_days, utl_rule_amount_of_days_opt, utl_rule_amount_of_posts, utl_rule_amount_of_posts_opt, utl_rule_max_posts, utl_rule_max_posts_opt
            FROM ' .  $this->utl_table . ' WHERE utl_rule_id=' . (int) $rule_id;

        }
        else
        {
            $sql =  'SELECT utl_rule_id, utl_rule_name, utl_rule_amount_of_days, utl_rule_amount_of_days_opt, utl_rule_amount_of_posts, utl_rule_amount_of_posts_opt, utl_rule_max_posts, utl_rule_max_posts_opt
                    FROM ' .  $this->utl_table;

        }

        $result = $db->sql_query($sql);
        $rule = $db->sql_fetchrow($result);

        return $rules;
    }

    private function proccess_rules($mode, $rules = false, $id = false)
    {
        if ($id !== false)
        {
            $old_rules = json_decode($this->get_existing_rules($id), true);
        }

        $test = array_replace($old_rules, $rules);
        echo "<pre>";
        echo "old";
        var_dump($old_rules);
        echo "new";
        var_dump($rules);
        echo "replaced";
        var_dump($test);
        echo "</pre>";

    }


}
