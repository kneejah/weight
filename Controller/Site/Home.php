<?php

	class Controller_Site_Home extends Abstract_Controller
	{

		public function GET()
		{
			// Do nothing, people can be logged in or out here
			print_r($_SESSION);
		}

	}