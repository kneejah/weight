<?php

	class View_Site_Login_Forgot_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'login';
			$app = Config::get('app');

			$policy = new Policy_LoggedOut($this->app);
			$logged_in = $policy->getData();

			$app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $logged_in);

			return array(
				'app'        => $app,
				'breadcrumb' => 'Forgot password',
				'error'      => Helper_Message::getError(),
				'success'    => Helper_Message::getSuccess()
			);
		}

	}