<?php

class Mapper_Settings
{

    private $_db;
    private static $_table = "user_settings";

    public function __construct()
    {
        $this->_db = new Stores_SQLite();
    }

    public function updateSettingForUserid($userid, $name, $value)
    {
        $oldSetting = $this->_getSettingByUserid($userid, $name);

        if ($oldSetting) {
            $query = "UPDATE " . self::$_table . " SET value=:value WHERE id=:id;";

            $data = array(
                ':id'    => $oldSetting['id'],
                ':value' => $value
            );

            return $this->_db->query($query, $data);
        } else {
            $data = array(
                'userid' => $userid,
                'name'   => $name,
                'value'  => $value
            );

            $res = $this->_db->insert(self::$_table, $data);

            return $res;
        }
    }

    public function getFilteredSettingsByUserid($userid)
    {
        $app = Config::get('app');
        $user_settings = $app->user_settings;

        $newSettings = array();

        // Get default settings
        foreach ($user_settings as $setting) {
            $newSettings[$setting['name']] = $setting['default'];
        }

        $newUserSettings = $this->_getSettingsByUserid($userid);

        // Now overwrite them with user values
        foreach ($newUserSettings as $setting) {
            $newSettings[$setting['name']] = $setting['value'];
        }

        return $newSettings;
    }

    private function _getSettingsByUserid($userid)
    {
        $query = "SELECT * FROM " . self::$_table . " WHERE userid=:userid;";
        $data = array(':userid' => $userid);

        $res = $this->_db->query($query, $data);

        return $res;
    }

    private function _getSettingByUserid($userid, $name)
    {
        $query = "SELECT * FROM " . self::$_table . " WHERE userid=:userid AND name=:name LIMIT 1;";
        $data = array(
            ':userid' => $userid,
            ':name'   => $name
        );

        $res = $this->_db->query($query, $data, true);

        return $res;
    }

    public function deleteAllSettingsForUser($userid)
    {
        $query = "DELETE FROM " . self::$_table . " WHERE userid=:userid;";

        $data = array(
            ':userid' => $userid
        );

        return $this->_db->query($query, $data);
    }

}
