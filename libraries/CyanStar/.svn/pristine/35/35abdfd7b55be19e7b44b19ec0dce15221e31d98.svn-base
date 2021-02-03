<?php


class LibStrings
{

	static function wordCount($v)
	{
		$v = preg_replace("/\W+/", " ", $v);
		$v = preg_replace("/\s+/", " ", $v);
		$v = trim($v);
		
		$count = substr_count($v, " ");
		
		return $count + 1;
	}



	static function trimWord($string, $word)
	{
		
		for ($ax = 0; $ax < 100; $ax++)
		{
			$string = trim($string);
			
			if (substr($string, 0, strlen($word)) == $word)
			{
				$string = substr($string, strlen($word));
			}
			else if (substr($string, -strlen($word)) == $word)
			{
				$string = substr($string, 0, -strlen($word));
			}
			else
			{
				break;
			}
		}
	
		return $string;
	}




	
	static function obfuscateEmail($v, $filler = "xxxx")
	{
		$parts = explode("@", $v);
	
		if (isset($parts[1]))
			return $filler."@".$parts[1];
			
		return $v;
	}
	
	
	// Given an array of keywords, return a string with highlighted elements around the keywords

	static function highlight($string, $keywords)
	{
		if (count($keywords) > 0)
			return preg_replace('/(\W|^)('.implode('|', $keywords).')(\W|$)/i', '$1<span class="highlight">$2</span>$3', $string);
			
		return $string;
	}



	// Given any string, puts anchor tags around URLs within the string

	static function url_to_link($text) {
	  $text =
		preg_replace('!(^|([^\'"]\s*))' .
		  '([hf][tps]{2,4}:\/\/[^\s<>"\'()]{4,})!mi',
		  '$2<a rel="nofollow" href="$3">$3</a>', $text);
	  $text =
		preg_replace('!<a href="([^"]+)[\.:,\]]">!',
		'<a href="$1">', $text);
	  $text = preg_replace('!([\.:,\]])</a>!', '</a>$1',
		$text);
	  return $text;
	}



	
	

	static function truncate($desc, $length, $trailer = "...")
	{
		$len = strlen($desc);
		
		if ($len > $length + strlen($trailer))
			return trim(substr($desc, 0, $length)) . $trailer;
			
		return $desc;
	}

	static function writtenImplode($array, $andor = "and", $delimit = ",")
	{
		// $andor could be "and", "or", or "nor"
	
		if (count($array) == 1)
			return $array[0];
			
		$andor = " ".$andor." ";
		
		if (count($array) == 2)
			return implode($andor, $array);


		$last = array_pop($array);
		return implode($delimit." ", $array).$delimit.$andor.$last;
	}




	static function removeExtraWhiteSpace($v)
	{
		return preg_replace("/\s+/", " ", $v);
	}
	


	
		
	static function recase($s)
	{
		$arr = str_split($s);	
		$output = "";
		$lastChar = "";
		
		foreach ($arr as $char)
		{
			if (empty($lastChar) || $lastChar == " ")
				$output .= strtoupper($char);
			else
				$output .= strtolower($char);

			$lastChar = $char;
		}
		
		return $output;
	}

	
	static function getPiece($haystack, $needle, $index)
	{
		$breaks = explode($needle, $haystack);
		
		if ($index < 0)
			$index += count($breaks);
		
		return isset($breaks[$index]) ? $breaks[$index] : false;
	}
	



	static function stringContains($hay, $inputs)
	{
		
		if (is_string($inputs))
			$needles[] = $inputs;
		else
			$needles = $inputs;
		
		foreach ($needles as $needle)
		{
			$v = strpos($hay, $needle);
			
			if ($v !== false)
				return true;
		}
		
		return false;
	}
	
	
	static function oneWithinOther($one, $two)
	{
		if (strlen($one) > strlen($two))
		{
			$lesser = $two;
			$greater = $one;
		}
		else
		{
			$lesser = $one;
			$greater = $two;
		}
		
		
		return strpos($greater, $lesser) !== false;
	}	
	
	

	
	static function wordCase($text)
	{	
		$exceptions[] = "to";
		$exceptions[] = "for";
		$exceptions[] = "the";
		$exceptions[] = "a";
		$exceptions[] = "an";
		$exceptions[] = "of";
		$exceptions[] = "at";

		$find = array();
		$put = array();
		
		foreach ($exceptions as $exc)
		{
			$find[] = " ".ucfirst($exc)." ";
			$put[] = " ".($exc)." ";
		}
		
		$words = ucwords(strtolower($text));
	
		return str_replace($find, $put, $words);
	}
	
	

	// If more than 50% of a string is written in UPPERCASE, it is lowercased.
	
	static function selectiveLowerCasing($text)
	{
		$matches;
		preg_match_all("/[A-Z]/", $text, $matches);

		$len = strlen($text);
		$cnt = count($matches[0]);
		
		if ($cnt + $cnt > $len)
			$text = ucfirst(strtolower($text));
			
		return $text;
	}
	
	
	static function obfuscateString($v)
	{
		return base64_encode($v);
	
	}

	static function obfuscate($v)
	{
		return base64_encode($v);
	
	}

	static function obfuscateReverse($v)
	{
		return base64_decode($v);
	
	}
	

	
	
	static function numericSuffix($number)
	{
		$suffix = "th";
	
		switch ($number)
		{
			case 1: $suffix = "st"; break;
			case 2: $suffix = "nd"; break;
			case 3: $suffix = "rd"; break;
		}
	
		return $number.$suffix;
	}
	
	static function spaceByCase($str)
	{
//		$str = str_replace('-', ' ', $str);
		$str = str_replace('_', ' ', $str);
		$str = preg_replace('/([a-z])([0-9])/', '$1 $2', $str);
		$str = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
		$str = str_replace('  ', ' ', $str);
		$str = str_replace(' - Copy', '', $str);
		$str = trim($str);
		return $str;
	}
	
	
	static function removeFileExtension($name)
	{
		$pieces = explode(".", $name);
		$lastIndex = count($pieces) - 1;
		
		if ($lastIndex > 0)
		{
			unset($pieces[$lastIndex]);
			
			return implode(".", $pieces);
		}
		
		return $name;
	}
	
	

	
	static function randomAlphanumericString($length, $omitVowels = false)
	{
		$string = "";
		$grabFrom = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		if ($omitVowels)
			$grabFrom = str_replace(array('A', 'E', 'I', 'O', 'U'), '', $grabFrom);

		$grabFrom .= strtolower($grabFrom);
		$grabFrom .= "0123456789";
		
		$len = strlen($grabFrom);
	
		for ($i=abs($length); $i > 0; $i--) 
		{
			$pos = mt_rand(0, $len);
			$string .= substr($grabFrom, $pos, 1);
		} 
		
		return $string;
	}
	
	

	static function renderStringWithArray($string, $array)
	{
		if (is_object($array))
			$array = get_object_vars($array);

		foreach ($array as $key => $value)
		{
			$string = str_replace("[$key]", $array[$key], $string);
		}

		return $string;
	}


	
	


}
