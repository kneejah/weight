<?php

	class Policy_LoggedIn extends Abstract_Policy
	{

		public function check()
		{
			return Helper_Session::getUserFromSession() !== false;
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