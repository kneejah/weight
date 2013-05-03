<?php

	class Controller_Site_Signup extends Abstract_Controller
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
			$email    = trim($request->post('email'));

			if ($username == "" || $password == "" || $email == "")
			{
				$this->error("All fields are required.");
			}

			if (strlen($username) < 5 || strlen($username) > 15)
			{
				$this->error("User name must be between 5 and 15 characters.");
			}

			if (strlen($password) < 5 || strlen($password) > 15)
			{
				$this->error("Password must be between 5 and 15 characters.");
			}

			if (!ctype_alnum($username))
			{
				$this->error("Invalid user name. Only letters and numbers are allowed.");
			}

			if (!ctype_alnum($password))
			{
				$this->error("Invalid password. Only letters and numbers are allowed.");
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$this->error("Invalid email format.");
			}

			$user_mapper = new Mapper_User();
			$possibleUser = $user_mapper->getUserByUsername($username);

			if ($possibleUser)
			{
				$this->error("That user name already exists.");
			}

			$possibleUser2 = $user_mapper->getUserByEmail($email);
			if ($possibleUser2)
			{
				$this->error("A user with that email address already exists.");
			}

			$user_mapper->createUser($username, $password, $email);

			$user = $user_mapper->getUserByUsername($username);

			$this->success($user['id']);
		}

		private function error($string)
		{
			$request = $this->app->request();
			Controller_Helper::setField($this->app, 'username', $request->post('username'));
			Controller_Helper::setField($this->app, 'email', $request->post('email'));
			Controller_Helper::setError($this->app, $string);

			$this->app->redirect('/signup');
			die();
		}

		private function success($userid)
		{
			Controller_Helper::setUserInSession($userid);
			$this->app->redirect('/home');
			die();
		}

	}