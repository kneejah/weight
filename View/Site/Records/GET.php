<?php

	class View_Site_Records_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'records';
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$userid = $policy->getData();

			$app->menu_items = Controller_Helper::processMenuItems($app->menu_items, $page, $userid);

			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getWeightsForUser($userid, 'all');

			foreach ($weights as &$weight)
			{
				$weight['time'] = date('F j, Y, g:i a', $weight['create_time']);
			}

			return array(
				'app'         => $app,
				'breadcrumb'  => 'Records',
				'has_weights' => (count($weights) > 0),
				'weights'     => $weights
			);
		}

	}