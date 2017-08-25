<?php

class Policy_LoggedIn extends Abstract_Policy
{

    public function check()
    {
        $isLoggedIn = Helper_Session::getUserFromSession() !== false;

        return $isLoggedIn;
    }

    public function success()
    {
        return true;
    }

    public function failure()
    {
        $request = $this->app->request();
        $next = $request->getResourceUri();
        $this->app->redirect("/login?next=$next");
        die();
    }

    public function getData()
    {
        return Helper_Session::getUserFromSession();
    }

}
