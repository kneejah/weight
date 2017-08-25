<?php

class Policy_LoggedOut extends Abstract_Policy
{

    public function check()
    {
        return Helper_Session::getUserFromSession() === false;
    }

    public function success()
    {
        return true;
    }

    public function failure()
    {
        $this->app->redirect('/');
        die();
    }

    public function getData()
    {
        return false;
    }

}