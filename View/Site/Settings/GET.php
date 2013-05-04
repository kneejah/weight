<?php

	class View_Site_Settings_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'settings';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$userid = $policy->getData();

			$app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $userid);

			// @TODO: enforce order
			$userSettings = $app->user_settings;

			$settings_mapper = new Mapper_Settings();
			$settingsVals = $settings_mapper->getFilteredSettingsByUserid($userid);

			foreach ($userSettings as &$setting)
			{
				$setting['value'] = $settingsVals[$setting['name']];
			}

			return array(
				'app'           => $app,
				'breadcrumb'    => 'Settings',
				'user_settings' => $userSettings,
				'error'         => Helper_Message::getError(),
				'success'       => Helper_Message::getSuccess(),
			);
		}

	}