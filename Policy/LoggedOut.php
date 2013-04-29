<?php

	class Policy_LoggedOut extends Abstract_Policy
	{

		public function check()
		{
			return Controller_Helper::getUserFromSession() === false;
		}

		public function success()
		{
			return true;
		}

		public function failure()
		{
			$this->app->redirect('/home');
			die();
		}

		public function getData()
		{
			return false;
		}

	}