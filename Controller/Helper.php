<?php

	class Controller_Helper
	{

		public static function processMenuItems($items, $page, $logged_in)
		{
			foreach ($items as $i => &$item)
			{
				if (!$logged_in && isset($item['logged_in']) && $item['logged_in'])
				{
					unset($items[$i]);
				}
				else if ($logged_in && isset($item['logged_out']) && $item['logged_out'])
				{
					unset($items[$i]);
				}

				if ($item['page'] == $page)
				{
					$item['current'] = true;
				}
			}

			return array_values($items);
		}

		public static function getField($field, $default = "")
		{
			$flash = $_SESSION['slim.flash'];

			if (isset($flash[$field]))
			{
				return $flash[$field];
			}
			else
			{
				return $default;
			}
		}

		public static function setField($app, $key, $val)
		{
			$app->flash($key, $val);
		}

		public static function getError()
		{
			return self::getField('error', false);
		}

		public static function setError($app, $string)
		{
			$app->flash('error', $string);
		}

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