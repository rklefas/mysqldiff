<?php


class Arrays
{

	# made this for amy, crestwoods

	function element_move($array, $moving, $after)
	{
		$content = $array[$moving];
		unset($array[$moving]);
		
		
		$rebuild = array();
		
		foreach ($array as $key => $val)
		{
			$rebuild[$key] = $val;
		
			if ($key == $after)
			{
				$rebuild[$moving] = $content;
			}
		
		}

		return $rebuild;

	}


	static function expandNumericalRanges($string)
	{
		$numbers = array();
		
		$splodes = explode(",", str_replace(" ", "", $string));
	
		foreach ($splodes as $num)
		{
			$ranges = explode(":", $num);
			
			if (count($ranges) == 1)
			{
				if ($ranges[0])
					$numbers[] = $ranges[0];
			}
			else if (count($ranges) == 2)
			{
				if (is_numeric($ranges[0]) && is_numeric($ranges[1]))
				{
					for ($ix = $ranges[0]; $ix <= $ranges[1]; $ix++)
					{
						if ($ix)
							$numbers[] = ($ix);
					}
				}
			}
		}
	
		
	
	
		$numbers = array_unique($numbers);
		sort($numbers);
	
		return $numbers;
	}



	static function indexesNotEmpty($arr, $indexes = null)
	{
		if ($indexes)
			$filter = self::whiteList($arr, $indexes);
		else
			$filter = $arr;
		
		foreach ($filter as $value)
		{
			if (empty($value))
				return false;
		}
	
	
		return true;
	
	}
	
	
	static function indexesWithContent($arr, $indexes = null)
	{
		if ($indexes)
			$filter = self::whiteList($arr, $indexes);
		else
			$filter = $arr;
			
		$total = 0;
		
		foreach ($filter as $value)
		{
			if (!empty($value))
				$total++;
		}
	
	
		return $total;
	
	}	
	

	
	static function getByNumericIndex($arr, $pos)
	{
		$arr = array_values($arr);
	
		if ($pos < 0)
		{
			$count = count($arr);
			$pos = $count + $pos;
		}
		
		return $arr[$pos];

	}


	static function getFromArray($array, $key, $default = null)
	{
		if (is_object($array))
			$array = get_object_vars($array);

		if (isset($array[$key]))
			return $array[$key];
	
		return $default;
	}



	static function randomIndex($arr)
	{
		$keys = array_keys($arr);
	
		return $arr[$keys[rand(0, count($keys) - 1)]];
	}



	static function removeEmptyElements($source)
	{
		$new = array();
		
		foreach ($source as $key => $val)
		{
			if (empty($key) || empty($val))
				continue;
				
			$new[$key] = $val;
		
		}
	
		return $new;
	}

	
	// Given a two-dimensional array, $column is pulled out of each containing array
	// giving you a single dimension array with just the values of $column.
	
	static function extractColumn($src, $column)
	{
		$return = array();
	
		foreach ($src as $key => $value)
		{
			if (is_object($value))
				$return[$key] = $value->$column;
			else if (is_array($value))
				$return[$key] = $value[$column];
			else
				$return[$key] = $value;
		}
	
		return $return;
	}
	
	

	
	// Empty values in $addon do not overwrite non-empty values in $source
	
	static function optimalMerge()
	{
		$secondMerge = array();
		
		foreach (func_get_args() as $array)
		{
			if (is_object($array))
				$array = get_object_vars($array);

			foreach ($array as $key => $val)
			{
				if (isset($secondMerge[$key]) && strlen($secondMerge[$key]) > strlen($val))
				{
					continue;
				}
				
				$secondMerge[$key] = $val;

			}		
		}
		
		return $secondMerge;
	}


	// Return source associative array with the keynames changed to what is specified in $keyArray
	// $keyArray[oldkey] = "newkey"
	// $source[oldkey] .. becomes .. $source[newkey]
	
	static function alternateKeyNames($source, $keyArray)
	{
	
		$newArray = array();
		
		foreach ($keyArray as $oldkey => $newkey)
		{
			if (array_key_exists($oldkey, $source))
			{
				$newArray[$newkey] = $source[$oldkey];
			}
		}
	
		return $newArray;
	
	}
	
	
	// returns $srcArray with only the $allowed indexes in the array
	// $allowed may either be an array, or a string that is delimited with $stringDelimiter

	static function whiteList($srcArray, $allowed, $stringDelimiter = ",", $returnAllGivenIndexes = true)
	{
		if (is_object($srcArray))
			$srcArray = get_object_vars($srcArray);
		else if (!is_array($srcArray))
			return array();
	
		$newArray = array();
		
		if (is_string($allowed))
			$allowed = explode($stringDelimiter, $allowed);
	
		foreach ($allowed as $allow)
		{
			if ($aspos = stripos($allow, " as "))
			{
				$orgallow = substr($allow, 0, $aspos);
				$newallow = substr($allow, $aspos + 3);
			}
			else
			{
				$newallow = $allow;
				$orgallow = $allow;
			}
			
			$newallow = trim($newallow);
			$orgallow = trim($orgallow);
			
			if (array_key_exists($orgallow, $srcArray))
				$newArray[$newallow] = $srcArray[$orgallow];
			else if ($returnAllGivenIndexes)
				$newArray[$newallow] = null;
		}
	
		return $newArray;
	}
	
	
	
	// returns $srcArray without the $notAllowed indexes in the array
	// $notAllowed may either be an array, or a string that is delimited with $stringDelimiter

	static function blackList($srcArray, $notAllowed, $stringDelimiter = ",")
	{
		if (is_object($srcArray))
			$srcArray = get_object_vars($srcArray);

		if (is_string($notAllowed))
			$notAllowed = explode($stringDelimiter, $notAllowed);

		foreach($notAllowed as $notAllow)
		{
			$notAllow = trim($notAllow);

			if (array_key_exists($notAllow, $srcArray))
				unset($srcArray[$notAllow]);
		}

		return $srcArray;
	}
	
	
	
	
	static function isNumeric($array)
	{	
		if (is_array($array) == false)
			return false;
	
		foreach ($array as $key => $val)
		{
			if (is_int($key) == false && ctype_digit($key) == false)
				return false;
		
		}
	
		return true;
	}
	
	
	
	static function isAssociative($array)
	{
		if (is_array($array) == false)
			return false;

		return self::isNumeric($array) == false;
	}
	



	
}
