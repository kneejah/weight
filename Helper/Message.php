<?php

	class Helper_Message
	{

		public static function getField($field, $default = "")
		{
			if (!isset($_SESSION) || !isset($_SESSION['slim.flash']))
			{
				return $default;
			}

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

		public static function getSuccess()
		{
			return self::getField('success', false);
		}

		public static function setSuccess($app, $string)
		{
			$app->flash('success', $string);
		}

	}