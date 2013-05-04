<?php

	class Helper_Weight
	{

		public static function getBMIForUser($userid)
		{
			$weight_mapper = new Mapper_Weight();
			$weight = $weight_mapper->getMostRecentWeightForUser($userid);

			$settings_mapper = new Mapper_Settings();
			$settings = $settings_mapper->getFilteredSettingsByUserid($userid);
			$height = $settings['height'];

			if (!$weight)
			{
				return "no_weights";
			}
			else if (!$height)
			{
				return "no_height";
			}

			$multiplier = 703.06957964;

			$val = ($weight / ($height * $height)) * $multiplier;

			return round($val, 2);
		}

		public static function getStatsForUser($userid, $days_back)
		{
			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getWeightsForUser($userid, $days_back);

			if (!$weights)
			{
				return array(
					'avg'             => 'N/A',
					'min'             => 'N/A',
					'max'             => 'N/A',
					'change_weight'   => 'N/A',
					'change_per_day'  => 'N/A',
					'change_per_week' => 'N/A'
				);
			}

			$total = 0;
			$min   = (float) $weights[0]['weight'];
			$max   = (float) $weights[0]['weight'];

			foreach ($weights as $weight)
			{
				$currWeight = (float) $weight['weight'];
				$total += $currWeight;

				if ($currWeight > $max)
				{
					$max = $currWeight;
				}

				if ($currWeight < $min)
				{
					$min = $currWeight;
				}

			}

			$avg = $total / count($weights);

			$changeWeight  = 'N/A'; 
			$changePerDay  = 'N/A';
			$changePerWeek = 'N/A';

			if (count($weights) > 1)
			{
				$startWeight = $weights[count($weights) - 1];
				$endWeight   = $weights[0];

				$diffWeight = $endWeight['weight'] - $startWeight['weight'];
				$diffTime   = $endWeight['create_time'] - $startWeight['create_time'];

				$diffDays = ceil($diffTime / (60 * 60 * 24));

				$changeWeight  = round($diffWeight, 1);
				$changePerDay  = $diffWeight / $diffDays;
				$changePerWeek = $changePerDay * 7;

				$changePerDay  = round($changePerDay, 1);
				$changePerWeek = round($changePerWeek, 1);
			}

			return array(
				'avg'             => round($avg, 1),
				'min'             => $min,
				'max'             => $max,
				'change_weight'   => $changeWeight,
				'change_per_day'  => $changePerDay,
				'change_per_week' => $changePerWeek
			);
		}

	}