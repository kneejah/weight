<?php

class Config_App extends Abstract_Config
{

    public function configs()
    {
        return array(
            'name' => 'Trackly',
            'asset_version' => self::_getGitCommitHash(),
            'menu_items' => array(
                0 => array('page' => 'home',     'name' => 'Home',     'url' => '/'),
                1 => array('page' => 'records',  'name' => 'Records',  'url' => '/records',  'logged_in' => true),
                2 => array('page' => 'settings', 'name' => 'Settings', 'url' => '/settings', 'logged_in' => true),
                3 => array('page' => 'tools',    'name' => 'Tools',    'url' => '/tools',    'logged_in' => true),
                4 => array('page' => 'about',    'name' => 'About',    'url' => '/about'),
                5 => array('page' => 'signup',   'name' => 'Sign up',  'url' => '/signup',   'logged_out' => true),
                6 => array('page' => 'login',    'name' => 'Log in',   'url' => '/login',    'logged_out' => true),
                7 => array('page' => 'logout',   'name' => 'Log out',  'url' => '/logout',   'logged_in' => true)
            ),
            'weight_units'     => 'lbs',
            'height_units'     => 'in',
            'default_timezone' => 'America/Los_Angeles',
            'user_settings' => array(
                0 => array(
                    'name'         => 'graph_smoothing',
                    'title'        => 'Smooth graph',
                    'description'  => 'Show a smoothed graph on the main page',
                    'type_boolean' => true,
                    'default'      => 0,
                    'validate'     => 'boolean',
                    'order'        => 5
                ),
                1 => array(
                    'name'         => 'height',
                    'title'        => 'Height',
                    'description'  => 'Your height in inches',
                    'type_string'  => true,
                    'default'      => 0,
                    'validate'     => 'height',
                    'order'        => 1
                ),
                2 => array(
                    'name'         => 'records_entries',
                    'title'        => 'Records entries',
                    'description'  => 'Number of entries on Records page',
                    'type_string'  => true,
                    'default'      => 20,
                    'validate'     => 'records_entries',
                    'order'        => 6
                ),
                3 => array(
                    'name'         => 'trend_line',
                    'title'        => 'Trend line',
                    'description'  => 'Show your trend line on the graph as well',
                    'type_boolean' => true,
                    'default'      => 0,
                    'validate'     => 'boolean',
                    'order'        => 4
                ),
                4 => array(
                    'name'         => 'target_weight',
                    'title'        => 'Target weight',
                    'description'  => 'Target weight you are trying to achieve',
                    'type_string'  => true,
                    'default'      => 0,
                    'validate'     => 'weight',
                    'order'        => 2
                ),
                5 => array(
                    'name'         => 'show_target_weight',
                    'title'        => 'Show target weight',
                    'description'  => 'Show your target weight on the graph',
                    'type_boolean' => true,
                    'default'      => 0,
                    'validate'     => 'boolean',
                    'order'        => 3
                )
            )
        );
    }

    private static function _getGitCommitHash()
    {
        $output = array();
        exec("git rev-parse --short HEAD", $output);

        if (!$output || count($output) != 1 || $output[0] == "") {
            return 1;
        }

        $output = trim($output[0]);

        return $output;
    }

}
