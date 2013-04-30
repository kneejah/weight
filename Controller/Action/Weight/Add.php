<?php

	class Controller_Action_Weight_Add extends Abstract_Controller
	{

		public function POST()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();

			$userid = $policy->getData();
			$request = $this->app->request();

			$weight = trim($request->post('weight'));
			$date = trim($request->post('date'));
			$comment = trim($request->post('comment'));

			if ($weight == "")
			{
				$this->error("You must specify a weight!");
			}

			if (!is_numeric($weight))
			{
				$this->error("Invalid weight value!");
			}

			if ($weight <= 0 || $weight > 1000)
			{
				$this->error("Invalid weight range!");
			}

			$passedDate = false;
			if ($date != "")
			{
				$passedDate = strtotime($date);

				if (!$passedDate)
				{
					$this->error("Invalid date specified!");
				}

				if ($passedDate > time())
				{
					$this->error("Date can't be in the future!");
				}
			}

			$weight = round($weight, 2);

			$mapper = new Mapper_Weight();
			$mapper->addWeight($userid, $weight, $comment, $passedDate);

			$this->success();
		}

		private function success()
		{
			$this->app->redirect('/home');
			die();
		}

		private function error($error)
		{
			Controller_Helper::setError($this->app, $error);
			$this->app->redirect('/home');
			die();
		}

	}