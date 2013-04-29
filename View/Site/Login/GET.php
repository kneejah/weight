<?php

	class View_Site_Login_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'login';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$logged_in = $policy->getData();

			$app->menu_items = Controller_Helper::processMenuItems($app->menu_items, $page, $logged_in);

			return array(
				'app'        => $app,
				'breadcrumb' => 'Log in',
				'error'      => Controller_Helper::getError(),
				'username'   => Controller_Helper::getField('username'),
			);
		}

	}