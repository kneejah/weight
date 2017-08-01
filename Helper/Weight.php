<?php

	class Helper_Weight
	{

		public static function validateWeight($weight)
		{
			if ($weight == "")
			{
				return false;
			}

			if (!is_numeric($weight))
			{
				return false;
			}

			if ($weight <= 0 || $weight > 1000)
			{
				return false;
			}

			return round($weight, 1);
		}

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
					'avg'              => 'N/A',
					'min'              => 'N/A',
					'max'              => 'N/A',
					'change_weight'    => 'N/A',
					'change_per_day'   => 'N/A',
					'change_per_week'  => 'N/A',
					'change_per_month' => 'N/A',
					'raw_change'       => 0
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

			$changeWeight   = 'N/A';
			$changePerDay   = 'N/A';
			$changePerWeek  = 'N/A';
			$changePerMonth = 'N/A';

			$rawChangePerDay = 0;

			if (count($weights) > 1)
			{
				$startWeight = $weights[count($weights) - 1];
				$endWeight   = $weights[0];

				$diffWeight = $endWeight['weight'] - $startWeight['weight'];
				$diffTime   = $endWeight['create_time'] - $startWeight['create_time'];

				$diffDays = ceil($diffTime / (60 * 60 * 24));

				$changeWeight   = round($diffWeight, 1);
				$changePerDay   = $diffWeight / $diffDays;
				$changePerWeek  = $changePerDay * 7;
				$changePerMonth = $changePerDay * 30.5;

				$rawChangePerDay = $changePerDay;

				$changePerDay   = round($changePerDay, 1);
				$changePerWeek  = round($changePerWeek, 1);
				$changePerMonth = round($changePerMonth, 1);
			}

			return array(
				'avg'              => round($avg, 1),
				'min'              => $min,
				'max'              => $max,
				'change_weight'    => $changeWeight,
				'change_per_day'   => $changePerDay,
				'change_per_week'  => $changePerWeek,
				'change_per_month' => $changePerMonth,
				'raw_change'       => $rawChangePerDay
			);
		}

		public static function getTargetStatsForUser($userid, $changePerDay)
		{
			$weightToTarget = 'N/A';
			$timeToTarget   = 'N/A';
			$targetWeight   = 'N/A';

			$weight_mapper = new Mapper_Weight();
			$weight = $weight_mapper->getMostRecentWeightForUser($userid);

			$settings_mapper = new Mapper_Settings();
			$settings = $settings_mapper->getFilteredSettingsByUserid($userid);

			if ($settings['target_weight'] > 0)
			{
				$weightToTarget = $settings['target_weight'] - $weight;
				$targetWeight   = $settings['target_weight'];

				// Trying to lose weight, and they're losing it
				if ($weightToTarget < 0 && $changePerDay < 0)
				{
					$timeToTarget = $weightToTarget / $changePerDay;
				}
				else if ($weightToTarget > 0 && $changePerDay > 0)
				{
					$timeToTarget = $weightToTarget / $changePerDay;
				}
			}

			if ($timeToTarget != 'N/A')
			{
				if ($timeToTarget > 365)
				{
                                        $timeToTarget = $timeToTarget / 365;
                                        $targetUnits = 'year';
				}
				else if ($timeToTarget > ((30.5 * 2) - 7))
				{
					$timeToTarget = $timeToTarget / 30.5;
					$targetUnits = 'month';
				}
				else if ($timeToTarget > ((7 * 2) - 1))
				{
					$timeToTarget = $timeToTarget / 7;
					$targetUnits = 'week';
				}
				else
				{
					$targetUnits = 'day';
				}

				$timeToTarget = round($timeToTarget);

				if ($timeToTarget != 1)
				{
					$targetUnits = $targetUnits . 's';
				}

				if ($timeToTarget == 0)
				{
					$timeToTarget = 'less than a day';
				}
				else
				{
					$timeToTarget = 'about ' . $timeToTarget . ' ' . $targetUnits;
				}
			}

			if ($weightToTarget != 'N/A')
			{
				$weightToTarget = round(abs($weightToTarget), 1);
			}

			return array(
				'weight_to_target' => $weightToTarget,
				'time_to_target'   => $timeToTarget,
				'target_weight'    => $targetWeight
			);
		}

	}
