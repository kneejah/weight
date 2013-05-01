<?php

	class Mapper_Weight
	{

		private $db;
		private static $table = "weight";

		public function __construct()
		{
			$this->db = new Stores_SQLite();
		}

		public function addWeight($userid, $weight, $comment, $date)
		{
			if ($date)
			{
				$now = $date;
			}
			else
			{
				$now = time();
			}

			$data = array(
				'userid'      => $userid,
				'weight'      => $weight,
				'create_time' => $now,
				'comment'     => $comment
			);

			$this->db->insert(self::$table, $data);
		}

		public function getWeightsForUser($userid, $days_back)
		{
			$daysAgo = time() - (60 * 60 * 24 * $days_back);

			$query = "SELECT * FROM " . self::$table . " WHERE userid=:userid AND create_time > :days_ago ORDER BY create_time DESC;";
			$data = array(
				':userid'   => $userid,
				':days_ago' => $daysAgo
			);

			$res = $this->db->query($query, $data);

			return $res;
		}

	}
