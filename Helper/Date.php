<?php

	class Helper_Date
	{

		public static function validateDate($date)
		{
			if ($date == "")
			{
				return false;
			}

			$passedDate = strtotime($date);

			if (!$passedDate)
			{
				return false;
			}

			if ($passedDate > time())
			{
				return false;
			}

			return $passedDate;
		}

	}