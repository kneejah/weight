<?php

class Mapper_Weight
{

    private $_db;
    private static $_table = "user_weights";

    public function __construct()
    {
        $this->_db = new Stores_SQLite();
    }

    public function addWeight($userid, $weight, $comment, $date = false)
    {
        if ($date) {
            $now = $date;
        } else {
            $now = time();
        }

        $existingWeight = $this->getWeightForUserByDate($userid, $now);
        if ($existingWeight) {
            return $this->updateWeightForUser($userid, $existingWeight['id'], $weight, $comment);
        }

        $data = array(
            'userid'      => $userid,
            'weight'      => $weight,
            'create_time' => $now,
            'comment'     => $comment
        );

        return $this->_db->insert(self::$_table, $data);
    }

    public function getWeightForUserByDate($userid, $date)
    {
        $query = "SELECT * FROM " . self::$_table . " WHERE userid=:userid AND create_time = :date LIMIT 1;";
        $data = array(
            ':userid' => $userid,
            ':date'   => $date
        );

        return $this->_db->query($query, $data, true);
    }

    public function getWeightsForUser($userid, $days_back, $current_ts = null)
    {
        $endDate = time() + (60 * 5); // Add some time as a buffer

        if (is_numeric($current_ts)) {
            $endDate = $current_ts + 86400; // Add a day to include the entire end day
            $startDate = $endDate - (60 * 60 * 24 * $days_back) - 86400;
        } else if ($days_back == 'all') {
            $startDate = 0;
        } else if ($days_back == 'ytd') {
            $str = "1/1/" . date("Y");
            $startDate = strtotime($str);
        } else {
            $startDate = time() - (60 * 60 * 24 * $days_back);
        }

        $query = "SELECT * FROM " . self::$_table . " WHERE userid=:userid AND create_time > :start_date AND create_time < :end_date ORDER BY create_time DESC;";
        $data = array(
            ':userid'     => $userid,
            ':start_date' => $startDate,
            ':end_date'   => $endDate
        );

        return $this->_db->query($query, $data);
    }

    public function getPaginatedWeightsForUser($userid, $start, $limit)
    {
        $query = "SELECT * FROM " . self::$_table . " WHERE userid=:userid ORDER BY create_time DESC LIMIT :start, :limit;";

        $start = ($start - 1) * $limit;

        $data = array(
            ':userid' => $userid,
            ':start'  => $start,
            ':limit'  => $limit
        );

        return $this->_db->query($query, $data);
    }

    public function getWeightsCountForUser($userid)
    {
        $query = "SELECT COUNT(userid) AS cnt FROM " . self::$_table . " WHERE userid=:userid";

        $data = array(
            ':userid' => $userid
        );

        $ret = $this->_db->query($query, $data, true);
        return $ret['cnt'];
    }

    public function deleteWeightForUser($userid, $id)
    {
        $query = "DELETE FROM " . self::$_table . " WHERE userid=:userid AND id=:id;";

        $data = array(
            ':userid' => $userid,
            ':id'     => $id
        );

        return $this->_db->query($query, $data);
    }

    public function deleteAllWeightsForUser($userid)
    {
        $query = "DELETE FROM " . self::$_table . " WHERE userid=:userid;";

        $data = array(
            ':userid' => $userid
        );

        return $this->_db->query($query, $data);
    }

    public function updateWeightForUser($userid, $id, $weight, $comment)
    {
        $query = "UPDATE " . self::$_table . " SET weight=:weight, comment=:comment WHERE userid=:userid AND id=:id;";

        $data = array(
            ':userid'  => $userid,
            ':id'      => $id,
            ':weight'  => $weight,
            ':comment' => $comment
        );

        return $this->_db->query($query, $data);
    }

    public function getMostRecentWeightForUser($userid)
    {
        $query = "SELECT * FROM " . self::$_table . " WHERE userid=:userid ORDER BY create_time DESC LIMIT 1;";

        $data = array(
            ':userid' => $userid
        );

        $data = $this->_db->query($query, $data, true);

        if ($data) {
            return $data['weight'];
        } else {
            return false;
        }
    }

}
