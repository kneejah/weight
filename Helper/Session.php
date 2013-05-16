<?php

	class Helper_Session
	{

		public static function getFieldFromSession($field, $default)
		{
			if (isset($_SESSION) && isset($_SESSION[$field]))
			{
				return $_SESSION[$field];
			}
			else
			{
				return $default;
			}
		}

		public static function setFieldInSession($key, $value)
		{
			$_SESSION[$key] = $value;
		}

		public static function getUserFromSession()
		{
			if ($_SESSION && isset($_SESSION['id']) && is_numeric($_SESSION['id']) && $_SESSION['id'] > 0)
			{
				return $_SESSION['id'];
			}
			else
			{
				return false;
			}
		}

		public static function setUserInSession($userid)
		{
			$_SESSION['id'] = $userid;
		}

		public static function clearSession()
		{
			$_SESSION = array();
		}

		public static function isCurrentUserAdmin()
		{
			$userid = self::getUserFromSession();

			if ($userid)
			{
				return $userid == 4;
			}

			return false;
		}

	}