<?php

	class Mapper_User
	{

		private $db;
		private static $table = "users";

		public function __construct()
		{
			$this->db = new Stores_SQLite();
		}

		public function getUserById($id)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE id=:id LIMIT 1;";
			$data = array(':id' => $id);

			return $this->db->query($query, $data, true);
		}

		public function getUserByUsername($username)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE username=:username LIMIT 1;";
			$data = array(':username' => $username);

			return $this->db->query($query, $data, true);
		}

		public function getUserByEmail($email_address)
		{
			$query = "SELECT * FROM " . self::$table . " WHERE email_address=:email_address LIMIT 1;";
			$data = array(':email_address' => $email_address);

			return $this->db->query($query, $data, true);
		}

		public function createUser($username, $password, $email)
		{
			$now = time();
			$hash = self::generateHash($password);

			$data = array(
				'username'      => $username,
				'password_hash' => $hash,
				'email_address' => $email,
				'create_time'   => $now,
				'update_time'   => $now
			);

			return $this->db->insert(self::$table, $data);
		}

		public function updateEmailForUser($id, $email)
		{
			$query = "UPDATE " . self::$table . " SET email_address=:email_address WHERE id=:id;";

			$data = array(
				':id'            => $id,
				':email_address' => $email
			);

			return $this->db->query($query, $data);
		}

		public function updatePasswordForUser($id, $password)
		{
			$hash = self::generateHash($password);
			
			$query = "UPDATE " . self::$table . " SET password_hash=:password_hash WHERE id=:id;";

			$data = array(
				':id'            => $id,
				':password_hash' => $hash
			);

			return $this->db->query($query, $data);
		}

		public function updateUpdateTimeForUser($id)
		{
			$now = time();
			
			$query = "UPDATE " . self::$table . " SET update_time=:update_time WHERE id=:id;";

			$data = array(
				':id'          => $id,
				':update_time' => $now
			);

			return $this->db->query($query, $data);
		}

		public function deleteUserById($id)
		{
			$query = "DELETE FROM " . self::$table . " WHERE id=:id;";

			$data = array(
				':id' => $id
			);

			return $this->db->query($query, $data);
		}

		public static function generateHash($password)
		{
			$app = Config::get('system');
			$hash = hash_hmac("sha256", $password, $app->password_hash);

			return $hash;
		}

	}