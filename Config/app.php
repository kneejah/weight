<?php

	class Config_app extends Abstract_Config
	{

		public function configs()
		{
			return array(
				'name' => 'Weight Tracker',
				'menu_items' => array(
					0 => array('page' => 'home',     'name' => 'Home',     'url' => '/home'),
					1 => array('page' => 'settings', 'name' => 'Settings', 'url' => '/settings', 'logged_in' => true),
					2 => array('page' => 'about',    'name' => 'About',    'url' => '/about'),
					3 => array('page' => 'help',     'name' => 'Help',     'url' => '/help'),
					4 => array('page' => 'signup',   'name' => 'Sign up',  'url' => '/signup',   'logged_out' => true),
					5 => array('page' => 'login',    'name' => 'Log in',   'url' => '/login',    'logged_out' => true),
					6 => array('page' => 'logout',   'name' => 'Log out',  'url' => '/logout',   'logged_in' => true)
				)
			);
		}

	}