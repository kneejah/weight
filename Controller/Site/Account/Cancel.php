<?php

class Controller_Site_Account_Cancel extends Abstract_Controller
{

    public function POST()
    {
        $policy = new Policy_LoggedIn($this->app);
        $policy->ensure();
        $userid = $policy->getData();

        $request = $this->app->request();
        $tempPassword = $request->post('password');

        $user_mapper = new Mapper_User();
        $user = $user_mapper->getUserById($userid);

        if ($user['password_hash'] != Mapper_User::generateHash($tempPassword)) {
            $this->error("The password you entered was invalid.");
        } else {
            // Delete settings
            $settings_mapper = new Mapper_Settings();
            $settings_mapper->deleteAllSettingsForUser($userid);

            // Delete weights
            $weight_mapper = new Mapper_Weight();
            $weight_mapper->deleteAllWeightsForUser($userid);

            // Delete user last
            $user_mapper->deleteUserById($userid);
            $this->success();
        }
    }

    public function success()
    {
        Helper_Session::clearSession();
        $this->app->redirect('/');
        die();
    }

    public function error($string)
    {
        Helper_Message::setError($this->app, $string);
        $this->app->redirect('/account');
        die();
    }

}
