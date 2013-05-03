<?php

	class View_Site_Settings_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'settings';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$userid = $policy->getData();

			$app->menu_items = Controller_Helper::processMenuItems($app->menu_items, $page, $userid);

			// @TODO: merge in user settings, and order
			$user_settings = $app->user_settings;

			return array(
				'app'           => $app,
				'breadcrumb'    => 'Settings',
				'user_settings' => $user_settings
			);
		}

	}