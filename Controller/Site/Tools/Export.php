<?php

class Controller_Site_Tools_Export extends Abstract_Controller
{

    public function GET()
    {
        $policy = new Policy_LoggedIn($this->app);
        $policy->ensure();
        $userid = $policy->getData();

        $date = date('n.j.Y');
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename=weights-$date.csv");

        $mapper = new Mapper_Weight();
        $weights = $mapper->getWeightsForUser($userid, 'all');

        echo "Date,Weight,Comment\n";

        foreach ($weights as $weight) {
            $time = date('F j Y g:i a', $weight['create_time']);
            echo $time . "," . $weight['weight'];

            if ($weight['comment']) {
                echo ',' . self::_escapeCSVValue($weight['comment']);
            }

            echo "\n";
        }
    }

    private static function _escapeCSVValue($value)
    {
        $value = str_replace('"', '""', $value);
        if (preg_match('/,/', $value) || preg_match("/\n/", $value) || preg_match('/"/', $value)) {
            return '"' . $value . '"';
        } else {
            return $value;
        }
    }

}
