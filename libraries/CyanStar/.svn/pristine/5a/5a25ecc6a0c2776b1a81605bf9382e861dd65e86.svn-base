<?php




class Colors 
{


	static function generate($min = 7, $max = 15)
	{
		$digits = array();
		
		for ($i = 0; $i < 6; $i++)
			$digits[] = rand($min, $max);
	
		return self::crunch($digits);
	}


	static private function digit($v)
	{
		switch ($v)
		{
			case 10: return "A";
			case 11: return "B";
			case 12: return "C";
			case 13: return "D";
			case 14: return "E";
			case 15: return "F";
		}
		
		return $v;
	}


	static private function crunch($arr)
	{
		$string = "#";
		
		foreach ($arr as $dig)
			$string .= self::digit($dig);

		return $string;
	}


}