<?php

	class Mapper_Weight
	{

		private $db;
		private static $table = "weight";

		public function __construct()
		{
			$this->db = new Stores_SQLite();
		}

		public function addWeight($userid, $weight, $comment, $date = false)
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
			if ($days_back == 'all')
			{
				$daysBack = 0;
			}
			else
			{
				$daysBack = time() - (60 * 60 * 24 * $days_back);
			}

			$query = "SELECT * FROM " . self::$table . " WHERE userid=:userid AND create_time > :days_back ORDER BY create_time DESC;";
			$data = array(
				':userid'    => $userid,
				':days_back' => $daysBack
			);

			$res = $this->db->query($query, $data);

			return $res;
		}

		public function deleteWeightForUser($userid, $id)
		{
			$query = "DELETE FROM " . self::$table . " WHERE userid=:userid AND id=:id;";

			$data = array(
				':userid' => $userid,
				':id'     => $id
			);

			$this->db->query($query, $data);
		}

		public function updateWeightForUser($userid, $id, $weight, $comment)
		{
			$query = "UPDATE " . self::$table . " SET weight=:weight, comment=:comment WHERE userid=:userid AND id=:id;";

			$data = array(
				':userid'  => $userid,
				':id'      => $id,
				':weight'  => $weight,
				':comment' => $comment
			);

			$this->db->query($query, $data);
		}

	}
