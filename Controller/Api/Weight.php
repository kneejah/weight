<?php

	class Controller_Api_Weight extends Abstract_Controller
	{

		public function POST()
		{
			$policy = new Policy_LoggedIn($this->app);

			$userid = $policy->getData();
			$request = $this->app->request();

			if (!$userid)
			{
				throw new Exception_Api("Unable to authenticate.");
			}

			$weight = trim($request->post('weight'));
			$date = trim($request->post('date'));
			$comment = trim($request->post('comment'));

			if ($weight == "")
			{
				throw new Exception_Api("You must specify a weight.");
			}

			if (!is_numeric($weight))
			{
				throw new Exception_Api("Invalid weight value.");
			}

			if ($weight <= 0 || $weight > 1000)
			{
				throw new Exception_Api("Invalid weight range.");
			}

			$passedDate = false;
			if ($date != "")
			{
				$passedDate = strtotime($date);

				if (!$passedDate)
				{
					throw new Exception_Api("Invalid date specified.");
				}

				if ($passedDate > time())
				{
					throw new Exception_Api("Date can't be in the future.");
				}
			}

			$weight = round($weight, 1);

			$mapper = new Mapper_Weight();
			$mapper->addWeight($userid, $weight, $comment, $passedDate);

			return array();
		}

		public function PUT()
		{
			$policy = new Policy_LoggedIn($this->app);

			$userid = $policy->getData();
			$request = $this->app->request();

			if (!$userid)
			{
				throw new Exception_Api("Unable to authenticate.");
			}

			$id = $request->params('id');
			$weight = $request->params('weight');
			$comment = $request->params('comment');

			if ($weight == "")
			{
				throw new Exception_Api("You must specify a weight.");
			}

			if (!is_numeric($weight))
			{
				throw new Exception_Api("Invalid weight value.");
			}

			if ($weight <= 0 || $weight > 1000)
			{
				throw new Exception_Api("Invalid weight range.");
			}

			$weight = round($weight, 1);

			$mapper = new Mapper_Weight();
			$mapper->updateWeightForUser($userid, $id, $weight, $comment);
			
			return array();
		}

		public function DELETE()
		{
			$policy = new Policy_LoggedIn($this->app);

			$userid = $policy->getData();
			$request = $this->app->request();

			if (!$userid)
			{
				throw new Exception_Api("Unable to authenticate.");
			}

			$id = $request->params('id');

			$mapper = new Mapper_Weight();
			$mapper->deleteWeightForUser($userid, $id);

			return array('id' => $id);
		}

	}