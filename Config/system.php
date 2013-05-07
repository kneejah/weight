<?php

	class Config_system extends Abstract_Config
	{

		public function configs()
		{
			$iniSettings = parse_ini_file(APP_ROOT . 'Config/system.ini');

			return array(
				'routes' => array(
					'Site_Signup' => array(
						'name'    => 'Site_Signup',
						'uri'     => '/signup',
						'type'    => 'get,post',
						'options' => array('post' => array('noview' => true))
					),
					'Site_Home' => array(
						'name'  => 'Site_Home',
						'uri'   => '/',
						'type'  => 'get'
					),
					'Site_Records' => array(
						'name'  => 'Site_Records',
						'uri'   => '/records',
						'type'  => 'get'
					),
					'Site_About' => array(
						'name'   => 'Site_About',
						'uri'    => '/about',
						'type'   => 'get'
					),
					'Site_Settings' => array(
						'name'      => 'Site_Settings',
						'uri'       => '/settings',
						'type'      => 'get,post',
						'options'   => array('post' => array('noview' => true))
					),
					'Site_Login'  => array(
						'name'    => 'Site_Login',
						'uri'     => '/login',
						'type'    => 'get,post',
						'options' => array('post' => array('noview' => true))
					),
					'Site_Logout' => array(
						'name'    => 'Site_Logout',
						'uri'     => '/logout',
						'type'    => 'get',
						'options' => array('noview' => true)
					),
					'Api_Weights' => array(
						'name'    => 'Api_Weights',
						'uri'     => '/api/weights',
						'type'    => 'get',
						'options' => array('api' => true)
					),
					'Api_UserData' => array(
						'name'    => 'Api_UserData',
						'uri'     => '/api/userdata',
						'type'    => 'get',
						'options' => array('api' => true)
					),
					'Api_Weight' => array(
						'name'    => 'Api_Weight',
						'uri'     => '/api/weight',
						'type'    => 'post,put,delete',
						'options' => array('post' => array('api' => true), 'put' => array('api' => true), 'delete' => array('api' => true))
					)
				),
				'cookie' => new \Slim\Middleware\SessionCookie(
					array(
						'expires' => '2 weeks',
						'path' => '/',
						'domain' => null,
						'secure' => false,
						'httponly' => false,
						'name' => 'slim_session',
						'secret' => $iniSettings['cookie_secret'],
						'cipher' => MCRYPT_RIJNDAEL_256,
						'cipher_mode' => MCRYPT_MODE_CBC
					)
				),
				'password_hash' => $iniSettings['password_hash']
			);
		}

	}