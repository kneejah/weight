<?php

	class Controller_Site_Logout extends Abstract_Controller
	{

		public function GET()
		{
			Helper_Session::clearUserFromSession();
			$this->app->redirect('/home');
			die();
		}

	}