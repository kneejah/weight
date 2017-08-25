<?php

class View_Site_Account_GET extends Abstract_View
{

    public function render()
    {
        $page = 'settings';
        $app = Config::get('app');

        $policy = new Policy_LoggedIn($this->app);
        $userid = $policy->getData();

        $app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $userid);

        $mapper = new Mapper_User();
        $user = $mapper->getUserById($userid);

        return array(
            'app'        => $app,
            'breadcrumb' => 'Account',
            'user'       => $user,
            'error'      => Helper_Message::getError(),
            'success'    => Helper_Message::getSuccess()
        );
    }

}
