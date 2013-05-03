<?php

	class Controller_Site_Settings extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();
		}

		public function POST()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();

			$app = Config::get('app');
			$request = $this->app->request();
			$user_settings = $app->user_settings;

			foreach ($user_settings as $setting)
			{
				$val = $request->post($setting['name']);

				$newVal = $setting['default'];
				if ($setting['validate'] == 'boolean')
				{
					if ($val == 'on')
					{
						$newVal = 1;
					}
					else
					{
						$newVal = 0;
					}
				}

				echo "(saving {$setting['name']} to $newVal)";
			}
		}

	}