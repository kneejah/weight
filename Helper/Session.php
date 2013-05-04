<?php

	class Helper_Session
	{

		public static function setUserInSession($userid)
		{
			$_SESSION['id'] = $userid;
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

		public static function clearUserFromSession()
		{
			$_SESSION = array();
		}

	}