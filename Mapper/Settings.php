<?php

	class Mapper_Settings
	{

		private $db;
		private static $table = "user_settings";

		public function __construct()
		{
			$this->db = new Stores_SQLite();
		}

		public function updateSettingForUserid($userid, $name, $value)
		{
			$oldSetting = $this->getSettingByUserid($userid, $name);

			if ($oldSetting)
			{
				$query = "UPDATE " . self::$table . " SET value=:value WHERE id=:id;";

				$data = array(
					':id'    => $oldSetting['id'],
					':value' => $value
				);

				return $this->db->query($query, $data);
			}
			else
			{
				$data = array(
					'userid' => $userid,
					'name'   => $name,
					'value'  => $value
				);

				$res = $this->db->insert(self::$table, $data);

				return $res;
			}
		}

		public function getFilteredSettingsByUserid($userid)
		{
			$app = Config::get('app');
			$user_settings = $app->user_settings;

			$newSettings = array();

			// Get default settings
			foreach ($user_settings as $setting)
			{
				$newSettings[$setting['name']] = $setting['default'];
			}

			$newUserSettings = $this->getSettingsByUserid($userid);

			// Now overwrite them with user values
			foreach ($newUserSettings as $setting)
			{
				$newSettings[$setting['name']] = $setting['value'];
			}

			return $newSettings;
		}

		private function getSettingsByUserid($userid)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE userid=:userid;";
			$data = array(':userid' => $userid);

			$res = $this->db->query($query, $data);

			return $res;
		}

		private function getSettingByUserid($userid, $name)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE userid=:userid AND name=:name LIMIT 1;";
			$data = array(
				':userid' => $userid,
				':name'   => $name
			);

			$res = $this->db->query($query, $data, true);

			return $res;
		}

	}