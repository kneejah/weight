<?php

	class View_Site_Home_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'home';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$logged_in = $policy->check();

			$app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $logged_in);

			$user = null;
			$formatted_weights = array();
			$settingsVals = array();
			
			if ($logged_in)
			{
				$userid = $policy->getData();
				$user_mapper = new Mapper_User();
				$user = $user_mapper->getUserById($userid);

				$settings_mapper = new Mapper_Settings();
				$settingsVals = $settings_mapper->getFilteredSettingsByUserid($userid);
			}

			return array(
				'app'           => $app,
				'breadcrumb'    => 'Home',
				'error'         => Helper_Message::getError(),
				'logged_in'     => $logged_in,
				'user'          => $user,
				'user_settings' => $settingsVals
			);
		}

	}