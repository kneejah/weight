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
			$userid = $policy->getData();

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

				$settings_mapper = new Mapper_Settings();
				$settings_mapper->updateSettingForUserid($userid, $setting['name'], $newVal);
			}

			Controller_Helper::setSuccess($this->app, "Your settings were updated.");

			$this->app->redirect('/settings');
			die();
		}

	}