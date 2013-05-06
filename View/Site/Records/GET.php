<?php

	class View_Site_Records_GET extends Abstract_View
	{

		public function render()
		{
			$page = 'records';
			$recordsPerPage = 10;
			$app = Config::get('app');

			$policy = new Policy_LoggedIn($this->app);
			$userid = $policy->getData();

			$app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $userid);

			$request = $this->app->request();
			$page = trim($request->get('page'));

			if (!ctype_digit($page))
			{
				$page = 1;
			}

			$weight_mapper = new Mapper_Weight();
			$totalWeights = $weight_mapper->getWeightsCountForUser($userid);
			$weights = $weight_mapper->getPaginatedWeightsForUser($userid, $page, $recordsPerPage);

			$numPages = ceil($totalWeights / $recordsPerPage);

			if ($page > $numPages)
			{
				$page = 1;
			}

			$pagesArray = array();
			for ($i = 1; $i <= $numPages; $i++)
			{
				$data = array('page' => $i, 'selected' => ($i == $page ? true : false));
				$pagesArray[] = $data;
			}

			foreach ($weights as &$weight)
			{
				$weight['time'] = date('F j, Y, g:i a', $weight['create_time']);
				$wVal = $weight['weight'];
				if (round($wVal) == $wVal)
				{
					$weight['weight'] = round($wVal);
				}
			}

			return array(
				'app'         => $app,
				'breadcrumb'  => 'Records',
				'has_weights' => (count($weights) > 0),
				'weights'     => $weights,
				'pages'       => $pagesArray
			);
		}

	}