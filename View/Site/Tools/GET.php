<?php

	class View_Site_Tools_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'tools';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$userid = $policy->getData();

			$app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $userid);

			return array(
				'app'           => $app,
				'breadcrumb'    => 'Tools',
				'error'         => Helper_Message::getError(),
				'success'       => Helper_Message::getSuccess()
			);
		}

	}