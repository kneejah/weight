<?php

class Controller_Api_UserData extends Abstract_Controller
{

    public function GET()
    {
        $policy = new Policy_LoggedIn($this->app);
        $app = Config::get('app');

        $userid = $policy->getData();
        $request = $this->app->request();

        if (!$userid) {
            throw new Exception_Api("Unable to authenticate.");
        }

        $current_ts = trim($request->get('current_ts'));
        $days_back = trim($request->get('days_back'));

        if ($current_ts !='' && !is_numeric($current_ts)) {
            throw new Exception_Api('Missing or invalid current_ts field.');
        }

        if (!is_numeric($days_back) && $days_back != 'all' && $days_back != 'ytd') {
            throw new Exception_Api('Missing or invalid days_back field.');
        }

        $bmi = Helper_Weight::getBMIForUser($userid);
        $stats = Helper_Weight::getStatsForUser($userid, $days_back, $current_ts);

        $rawChange = $stats['raw_change'];
        unset($stats['raw_change']);

        $target = Helper_Weight::getTargetStatsForUser($userid, $rawChange);

        $data = array(
        'bmi'    => $bmi,
        'stats'  => $stats,
        'target' => $target
        );

        return array('data' => $data, 'units' => $app->weight_units);
    }

}
