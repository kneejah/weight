<?php

	class Policy_LoggedIn extends Abstract_Policy
	{

		public function check()
		{
			$isLoggedIn = Helper_Session::getUserFromSession() !== false;

			if ($isLoggedIn)
			{
				$userid = Helper_Session::getUserFromSession();
				$defaultTz = date_default_timezone_get();

				$mapper = new Mapper_Settings();
				$settings = $mapper->getFilteredSettingsByUserid($userid);

				$tzSet = date_default_timezone_set($settings['timezone']);

				if (!$tzSet)
				{
					date_default_timezone_set($defaultTz);
				}
			}

			return $isLoggedIn;
		}

		public function success()
		{
			return true;
		}

		public function failure()
		{
			$this->app->redirect('/login');
			die();
		}

		public function getData()
		{
			return Helper_Session::getUserFromSession();
		}

	}