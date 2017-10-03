<?php

class Controller_Site_Account extends Abstract_Controller
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

        $mapper = new Mapper_User();
        $user = $mapper->getUserById($userid);

        $app = Config::get('app');
        $request = $this->app->request();

        $email = trim($request->post('email'));

        if (!$email) {
            $this->error("Email is a required field.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email format.");
        }

        $possibleUser = $mapper->getUserByEmail($email);
        if ($possibleUser && $possibleUser['id'] != $userid) {
            $this->error("A user with that email address already exists.");
        }

        $oldPassword = trim($request->post('old_password'));
        $newPassword = trim($request->post('new_password'));

        if (($oldPassword && !$newPassword) || (!$oldPassword && $newPassword)) {
            $this->error("You must enter both your old and your new passwords.");
        } else if ($oldPassword && $newPassword) {
            if ($user['password_hash'] != Mapper_User::generateHash($oldPassword)) {
                $this->error("Old password is incorrect.");
            }

            if (strlen($newPassword) < 5 || strlen($newPassword) > 15) {
                $this->error("New password must be between 5 and 15 characters.");
            }

            if (!ctype_alnum($newPassword)) {
                $this->error("Invalid password. Only letters and numbers are allowed.");
            }

            $mapper->updatePasswordForUser($userid, $newPassword);
        }

        $mapper->updateEmailForUser($userid, $email);

        $this->success();
    }

    public function success()
    {
        Helper_Message::setSuccess($this->app, "Your account settings were updated.");
        $this->app->redirect('/account');
        die();
    }

    public function error($string)
    {
        Helper_Message::setError($this->app, $string);
        $this->app->redirect('/account');
        die();
    }

}
