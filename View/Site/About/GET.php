<?php

	class View_Site_About_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'about';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$userid = $policy->getData();

			$app->menu_items = Controller_Helper::processMenuItems($app->menu_items, $page, $userid);

			return array(
				'app'         => $app,
				'breadcrumb'  => 'About'
			);
		}

	}