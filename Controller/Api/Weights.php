<?php

	class Controller_Api_Weights extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$app = Config::get('app');

			$userid = $policy->getData();
			$request = $this->app->request();

			if (!$userid)
			{
				throw new Exception_Api("Unable to authenticate.");
			}

			$days_back = trim($request->get('days_back'));

			if (!is_numeric($days_back) && $days_back != 'all' && $days_back != 'ytd')
			{
				throw new Exception_Api('Missing or invalid days_back field.');
			}

			$mapper = new Mapper_Settings();
			$mapper->updateSettingForUserid($userid, 'default_view', $days_back);

			$settings = $mapper->getFilteredSettingsByUserid($userid);

			$serverDateTimeZone = new DateTimeZone($app->default_timezone);
			$userDateTimeZone   = new DateTimeZone($settings['timezone']);

			$serverDateTime = new DateTime("now", $serverDateTimeZone);
			$userDateTime   = new DateTime("now", $userDateTimeZone);

			$tzDiff = $userDateTime->getOffset() - $serverDateTime->getOffset();
			$tzDiff = $tzDiff / (60 * 60);

			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getWeightsForUser($userid, $days_back);

			$formatted_weights = array();
			foreach ($weights as $weight)
			{
				$formatted_weights[] =
					array(
						'date'    => $weight['create_time'],
						'weight'  => $weight['weight'],
						'comment' => htmlspecialchars($weight['comment'], ENT_QUOTES, "UTF-8")
					);
			}

			return array('data' => $formatted_weights, 'units' => $app->weight_units, 'tz_offset' => $tzDiff);
		}

	}
