<?php

	class Mapper_Weight
	{

		private $db;
		private static $table = "user_weights";

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

			return $this->db->insert(self::$table, $data);
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

			return $this->db->query($query, $data);
		}

		public function getPaginatedWeightsForUser($userid, $start, $limit)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE userid=:userid ORDER BY create_time DESC LIMIT :start, :limit;";

			$start = ($start - 1) * $limit;

			$data = array(
				':userid' => $userid,
				':start'  => $start,
				':limit'  => $limit
			);

			return $this->db->query($query, $data);
		}

		public function getWeightsCountForUser($userid)
		{
			$query = "SELECT COUNT(userid) AS cnt FROM " . self::$table . " WHERE userid=:userid";

			$data = array(
				':userid' => $userid
			);

			$ret = $this->db->query($query, $data, true);
			return $ret['cnt'];
		}

		public function deleteWeightForUser($userid, $id)
		{
			$query = "DELETE FROM " . self::$table . " WHERE userid=:userid AND id=:id;";

			$data = array(
				':userid' => $userid,
				':id'     => $id
			);

			return $this->db->query($query, $data);
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

			return $this->db->query($query, $data);
		}

		public function getMostRecentWeightForUser($userid)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE userid=:userid ORDER BY create_time DESC LIMIT 1;";

			$data = array(
				':userid' => $userid
			);

			$data = $this->db->query($query, $data, true);

			if ($data)
			{
				return $data['weight'];
			}
			else
			{
				return false;
			}
		}

	}
