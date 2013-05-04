<?php

	class Helper_Menu
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

	}