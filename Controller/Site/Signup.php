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

        if ($username == "" || $password == "" || $email == "") {
            $this->_error("All fields are required.");
        }

        if (strlen($username) < 5 || strlen($username) > 15) {
            $this->_error("User name must be between 5 and 15 characters.");
        }

        if (strlen($password) < 5 || strlen($password) > 15) {
            $this->_error("Password must be between 5 and 15 characters.");
        }

        if (!ctype_alnum($username)) {
            $this->_error("Invalid user name. Only letters and numbers are allowed.");
        }

        $firstChar = substr($username, 0, 1);
        if (!ctype_alpha($firstChar)) {
            $this->_error("User name must start with a letter.");
        }

        if (!ctype_alnum($password)) {
            $this->_error("Invalid password. Only letters and numbers are allowed.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_error("Invalid email format.");
        }

        $user_mapper = new Mapper_User();
        $possibleUser = $user_mapper->getUserByUsername($username);

        if ($possibleUser) {
            $this->_error("That user name already exists.");
        }

        $possibleUser2 = $user_mapper->getUserByEmail($email);
        if ($possibleUser2) {
            $this->_error("A user with that email address already exists.");
        }

        $user_mapper->createUser($username, $password, $email);

        $user = $user_mapper->getUserByUsername($username);

        $this->_success($user['id']);
    }

    private function _error($string)
    {
        $request = $this->app->request();
        Helper_Message::setField($this->app, 'username', $request->post('username'));
        Helper_Message::setField($this->app, 'email', $request->post('email'));
        Helper_Message::setError($this->app, $string);

        $this->app->redirect('/signup');
        die();
    }

    private function _success($userid)
    {
        Helper_Session::setUserInSession($userid);
        $this->app->redirect('/');
        die();
    }

}
