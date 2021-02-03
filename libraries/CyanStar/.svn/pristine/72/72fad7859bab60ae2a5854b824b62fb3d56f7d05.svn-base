<?php

class Dates
{

	static function adjustTimestamp($timestamp, $adjustment, $format)
	{
	
		$unixtime = strtotime($timestamp);

		$r_end = strtotime(date("m/d/Y H:i:s", $unixtime). " ".$adjustment);
	
		return date($format, $r_end);
	
	}

	
	
	
	static function timestamp($date, $format = 'r')
	{
	
		if ($date)
		{
			if (is_numeric($date))
				$unix = $date;
			else
				$unix = strtotime($date);
				
			return date($format, $unix);
		}
		
		return '';
	}

	
	

}