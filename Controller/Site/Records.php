<?php

class Controller_Site_Records extends Abstract_Controller
{

    public function GET()
    {
        $policy = new Policy_LoggedIn($this->app);
        $policy->ensure();
    }

}