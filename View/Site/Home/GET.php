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
					0 => array('value' => 7,        'name' => 'Last 1 week'),
					1 => array('value' => 31,       'name' => 'Last 1 month'),
					2 => array('value' => 61,       'name' => 'Last 2 months'),
					3 => array('value' => 91,       'name' => 'Last 3 months'),
					4 => array('value' => 183,      'name' => 'Last 6 months'),
					5 => array('value' => 365,      'name' => 'Last 1 year'),
					6 => array('value' => 'ytd',    'name' => 'Year to date'),
					7 => array('value' => 'all',    'name' => 'All data'),
					8 => array('value' => 'custom', 'name' => 'Custom date range')
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
