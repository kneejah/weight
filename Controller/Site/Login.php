<?php

	class Controller_Site_Login extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedOut($this->app);
			$policy->ensure();
		}

		public function POST()
		{
			$policy = new Policy_LoggedOut($this->app);
			$policy->ensure();

			$request = $this->app->request();

			$username = trim($request->post('username'));
			$password = trim($request->post('password'));

			if ($username == "" || $password == "")
			{
				$this->error("Both fields are required.");
			}

			$user_mapper = new Mapper_User();
			$user = $user_mapper->getUserByUsername($username);

			if (!$user)
			{
				$this->error("Invalid user name or password.");
			}

			$hash = Mapper_User::generateHash($password);

			if ($user['password_hash'] !== $hash)
			{
				$this->error("Invalid user name or password.");
			}

			$this->success($user['id']);
		}

		private function error($string)
		{
			$request = $this->app->request();
			Helper_Message::setField($this->app, 'username', $request->post('username'));
			Helper_Message::setError($this->app, $string);

			$this->app->redirect('/login');
			die();
		}

		private function success($userid)
		{
			Helper_Session::setUserInSession($userid);
			$this->app->redirect('/');
			die();
		}

	}