<?php

	class View_Site_Home_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'home';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$logged_in = $policy->check();

			$app->menu_items = Controller_Helper::processMenuItems($app->menu_items, $page, $logged_in);

			$user = null;
			$formatted_weights = array();

			if ($logged_in)
			{
				$userid = $policy->getData();
				$user_mapper = new Mapper_User();
				$user = $user_mapper->getUserById($userid);
			}

			return array(
				'app'        => $app,
				'breadcrumb' => 'Home',
				'error'      => Controller_Helper::getError(),
				'logged_in'  => $logged_in,
				'user'       => $user
			);
		}

	}