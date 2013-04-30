<?php

	class Controller_Api_Weights extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();

			$userid = $policy->getData();

			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getLastMonthsWeight($userid);

			foreach ($weights as $weight)
			{
				$formatted_weights[] =
					array(
						'date'    => $weight['create_time'],
						'weight'  => $weight['weight'],
						'comment' => $weight['comment']
					);
			}

			return $formatted_weights;
		}

	}