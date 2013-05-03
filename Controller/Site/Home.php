<?php

	class Controller_Site_Home extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$logged_in = $policy->check();

			if ($logged_in)
			{
				$userid = $policy->getData();

				$user_mapper = new Mapper_User();
				$user_mapper->updateUpdateTimeForUser($userid);
			}
		}

	}