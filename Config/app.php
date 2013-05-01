<?php

	class Config_app extends Abstract_Config
	{

		public function configs()
		{
			return array(
				'name' => 'wTracker',
				'menu_items' => array(
					0 => array('page' => 'home',     'name' => 'Home',     'url' => '/home'),
					1 => array('page' => 'records',  'name' => 'Records',  'url' => '/records',  'logged_in' => true),
					// 2 => array('page' => 'settings', 'name' => 'Settings', 'url' => '/settings', 'logged_in' => true),
					// 3 => array('page' => 'tools',    'name' => 'Tools',    'url' => '/tools',    'logged_in' => true),
					// 4 => array('page' => 'about',    'name' => 'About',    'url' => '/about'),
					2 => array('page' => 'signup',   'name' => 'Sign up',  'url' => '/signup',   'logged_out' => true),
					3 => array('page' => 'login',    'name' => 'Log in',   'url' => '/login',    'logged_out' => true),
					4 => array('page' => 'logout',   'name' => 'Log out',  'url' => '/logout',   'logged_in' => true)
				),
				'units' => 'lbs'
			);
		}

	}