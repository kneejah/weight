<?php

	class Controller_Api_Weights extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();

			$userid = $policy->getData();

			$request = $this->app->request();
			$days_back = $request->get('days_back');

			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getWeightsForUser($userid, $days_back);

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

	}
