<?php

	class Controller_Site_Logout extends Abstract_Controller
	{

		public function GET()
		{
			Controller_Helper::clearUserFromSession();
			$this->app->redirect('/home');
			die();
		}

	}