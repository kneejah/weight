<?php

class Config_System extends Abstract_Config
{

    public function configs()
    {
        $iniSettings = parse_ini_file(APP_ROOT . 'Config/system.ini');

        return array(
            'routes' => array(
                array(
                    'name'    => 'Site_Signup',
                    'uri'     => '/signup',
                    'type'    => 'get,post',
                    'options' => array('post' => array('noview' => true))
                ),
                array(
                    'name' => 'Site_Home',
                    'uri'  => '/',
                    'type' => 'get'
                ),
                array(
                    'name' => 'Site_Records',
                    'uri'  => '/records',
                    'type' => 'get'
                ),
                array(
                    'name' => 'Site_About',
                    'uri'  => '/about',
                    'type' => 'get'
                ),
                array(
                    'name' => 'Site_Tools',
                    'uri'  => '/tools',
                    'type' => 'get'
                ),
                array(
                    'name'    => 'Site_Tools_Import',
                    'uri'     => '/tools/import',
                    'type'    => 'post',
                    'options' => array('noview' => true)
                ),
                array(
                    'name'    => 'Site_Tools_Export',
                    'uri'     => '/tools/export',
                    'type'    => 'get',
                    'options' => array('noview' => true)
                ),
                array(
                    'name'    => 'Site_Settings',
                    'uri'     => '/settings',
                    'type'    => 'get,post',
                    'options' => array('post' => array('noview' => true))
                ),
                array(
                    'name'    => 'Site_Account',
                    'uri'     => '/account',
                    'type'    => 'get,post',
                    'options' => array('post' => array('noview' => true))
                ),
                array(
                    'name'    => 'Site_Account_Cancel',
                    'uri'     => '/account/cancel',
                    'type'    => 'post',
                    'options' => array('noview' => true)
                ),
                array(
                    'name'    => 'Site_Login',
                    'uri'     => '/login',
                    'type'    => 'get,post',
                    'options' => array('post' => array('noview' => true))
                ),
                array(
                    'name'    => 'Site_Login_Forgot',
                    'uri'     => '/login/forgot',
                    'type'    => 'get,post',
                    'options' => array('post' => array('noview' => true))
                ),
                array(
                    'name'    => 'Site_Logout',
                    'uri'     => '/logout',
                    'type'    => 'get',
                    'options' => array('noview' => true)
                ),
                array(
                    'name'    => 'Api_Weights',
                    'uri'     => '/api/weights',
                    'type'    => 'get',
                    'options' => array('api' => true)
                ),
                array(
                    'name'    => 'Api_UserData',
                    'uri'     => '/api/userdata',
                    'type'    => 'get',
                    'options' => array('api' => true)
                ),
                array(
                    'name'    => 'Api_Weight',
                    'uri'     => '/api/weight',
                    'type'    => 'post,put,delete',
                    'options' => array('post' => array('api' => true), 'put' => array('api' => true), 'delete' => array('api' => true))
                )
            ),
            'cookie' => new \Slim\Middleware\SessionCookie(
                array(
                    'expires' => '6 months',
                    'path' => '/',
                    'domain' => null,
                    'secure' => true,
                    'httponly' => false,
                    'name' => 'slim_session',
                    'secret' => $iniSettings['cookie_secret'],
                    'cipher' => MCRYPT_RIJNDAEL_256,
                    'cipher_mode' => MCRYPT_MODE_CBC
                )
            ),
            'password_hash' => $iniSettings['password_hash'],
            'sqlite_path'   => $iniSettings['sqlite_path']
        );
    }

}
