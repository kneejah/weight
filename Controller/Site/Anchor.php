<?php

	class Controller_Site_Anchor extends Abstract_Controller
	{

		public function GET()
		{
			$this->app->redirect('/home');
			die();
		}

	}