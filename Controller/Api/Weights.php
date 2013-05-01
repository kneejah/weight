<?php

	class Controller_Api_Weights extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();

			$userid = $policy->getData();

			$request = $this->app->request();
			$days_back = trim($request->get('days_back'));

			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getWeightsForUser($userid, $days_back);

			if (!is_numeric($days_back) && $days_back != 'all')
			{
				Controller_Helper::apiError('Missing or invalid days_back field');
			}

			$formatted_weights = array();
			foreach ($weights as $weight)
			{
				$formatted_weights[] =
					array(
						'date'    => $weight['create_time'],
						'weight'  => $weight['weight'],
						'comment' => htmlentities($weight['comment'])
					);
			}

			return $formatted_weights;
		}

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
				Controller_Helper::apiError("You must specify a weight!");
				// die();
			}

			if (!is_numeric($weight))
			{
				Controller_Helper::apiError("Invalid weight value!");
				// die();
			}

			if ($weight <= 0 || $weight > 1000)
			{
				Controller_Helper::apiError("Invalid weight range!");
				// die();
			}

			$passedDate = false;
			if ($date != "")
			{
				$passedDate = strtotime($date);

				if (!$passedDate)
				{
					Controller_Helper::apiError("Invalid date specified!");
					// die();
				}

				if ($passedDate > time())
				{
					Controller_Helper::apiError("Date can't be in the future!");
					// die();
				}
			}

			$weight = round($weight, 2);

			$mapper = new Mapper_Weight();
			$mapper->addWeight($userid, $weight, $comment, $passedDate);

			return array();
		}

	}
