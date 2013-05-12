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
			$request = $this->app->request();
			$next = $request->getResourceUri();
			$this->app->redirect("/login?next=$next");
			die();
		}

		public function getData()
		{
			return Helper_Session::getUserFromSession();
		}

	}