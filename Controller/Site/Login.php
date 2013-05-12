<?php

	class Controller_Site_Login extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedOut($this->app);
			$policy->ensure();

			$request = $this->app->request();
			$next    = $request->get('next');

			if ($next)
			{
				Helper_Message::setField($this->app, 'next', $next);
			}
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

			$next = Helper_Message::getField('next', "");

			if ($next)
			{
				$this->app->redirect("/login?next=$next");
			}
			else
			{
				$this->app->redirect('/login');
			}

			die();
		}

		private function success($userid)
		{
			Helper_Session::setUserInSession($userid);

			$next    = Helper_Message::getField('next', "");
			$matches = array();
			preg_match('#/[A-Za-z0-9]+#i', $next, $matches);
			$nextFound = count($matches) == 1 ? $matches[0] : "";

			if ($next !== $nextFound)
			{
				$next = "";
			}

			if ($next)
			{
				$this->app->redirect($next);
			}
			else
			{
				$this->app->redirect('/');
			}
			die();
		}

	}