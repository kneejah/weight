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
				Controller_Helper::apiError("Unable to authenticate");
			}

			$weight = trim($request->post('weight'));
			$date = trim($request->post('date'));
			$comment = trim($request->post('comment'));

			if ($weight == "")
			{
				Controller_Helper::apiError("You must specify a weight");
			}

			if (!is_numeric($weight))
			{
				Controller_Helper::apiError("Invalid weight value");
			}

			if ($weight <= 0 || $weight > 1000)
			{
				Controller_Helper::apiError("Invalid weight range");
			}

			$passedDate = false;
			if ($date != "")
			{
				$passedDate = strtotime($date);

				if (!$passedDate)
				{
					Controller_Helper::apiError("Invalid date specified");
				}

				if ($passedDate > time())
				{
					Controller_Helper::apiError("Date can't be in the future");
				}
			}

			$weight = round($weight, 2);

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
				Controller_Helper::apiError("Unable to authenticate");
			}

			$id = $request->params('id');
			$weight = $request->params('weight');
			$comment = $request->params('comment');

			if ($weight == "")
			{
				Controller_Helper::apiError("You must specify a weight");
			}

			if (!is_numeric($weight))
			{
				Controller_Helper::apiError("Invalid weight value");
			}

			if ($weight <= 0 || $weight > 1000)
			{
				Controller_Helper::apiError("Invalid weight range");
			}

			$weight = round($weight, 2);

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
				Controller_Helper::apiError("Unable to authenticate");
			}

			$id = $request->params('id');

			$mapper = new Mapper_Weight();
			$mapper->deleteWeightForUser($userid, $id);

			return array('id' => $id);
		}

	}