<?php

class Controller_Site_Logout extends Abstract_Controller
{

    public function GET()
    {
        Helper_Session::clearSession();
        $this->app->redirect('/');
        die();
    }

}