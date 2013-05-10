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

			$userSettings = $app->user_settings;
			usort($userSettings, array('self', 'sortSettings'));

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
				'success'       => Helper_Message::getSuccess()
			);
		}

		private static function sortSettings($a, $b)
		{
			return $a['order'] - $b['order'];
		}

	}
