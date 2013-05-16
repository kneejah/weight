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

			$user              = null;
			$formatted_weights = array();
			$settingsVals      = array();
			$viewOptions       = array();

			if ($logged_in)
			{
				$userid = $policy->getData();
				$user_mapper = new Mapper_User();
				$user = $user_mapper->getUserById($userid);

				$settings_mapper = new Mapper_Settings();
				$settingsVals = $settings_mapper->getFilteredSettingsByUserid($userid);

				$defaultView = 30.5;
				if (isset($settingsVals['default_view']))
				{
					$defaultView = $settingsVals['default_view'];
				}

				$viewOptions = array(
					0 => array('value' => 7,     'name' => '1 week'),
					1 => array('value' => 30.5,  'name' => '1 month'),
					2 => array('value' => 182.5, 'name' => '6 months'),
					3 => array('value' => 365,   'name' => '1 year'),
					4 => array('value' => 'ytd', 'name' => 'Year to date'),
					5 => array('value' => 'all', 'name' => 'All data'),
				);

				foreach ($viewOptions as &$option)
				{
					if ($option['value'] == $defaultView)
					{
						$option['selected'] = true;
					}
				}
			}

			return array(
				'app'           => $app,
				'breadcrumb'    => 'Home',
				'error'         => Helper_Message::getError(),
				'logged_in'     => $logged_in,
				'user'          => $user,
				'user_settings' => $settingsVals,
				'view_options'  => $viewOptions
			);
		}

	}