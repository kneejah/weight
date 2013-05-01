<?php

	class Controller_Api_Weight extends Abstract_Controller
	{

		public function DELETE()
		{
			$policy = new Policy_LoggedIn($this->app);

			$userid = $policy->getData();
			$request = $this->app->request();

			if (!$userid)
			{
				Controller_Helper::apiError("Unable to authenticate!");
			}

			$id = $request->params('id');

			$mapper = new Mapper_Weight();
			$mapper->deleteWeightForUser($userid, $id);

			return array('id' => $id);
		}

	}