<?php

class DataMining
{

	static function extractKeywords($v, $limit = 10)
	{
		$text = preg_replace("/[^a-zA-Z0-9\s]/", "", $v);
		$text = trim($text);
		$text = strtolower($text);
		$text = str_replace("  ", " ", $text);
		$plodes = explode(" ", $text);
		
		$keywords = array();
		
		foreach ($plodes as $word)
		{
			$word = trim($word); 
			
			if (strlen($word) < 5)
				continue;
			
			if (isset($keywords[$word]) == false)
				$keywords[$word] = 0;
			
			$keywords[$word]++;
		}
		
		arsort($keywords);
		$keywords = array_slice($keywords, 0, $limit);
		
		return $keywords;
		
	}

	static function orderKeywords($v, $limit = 10)
	{
		$text = trim($v);
		$plodes = explode(",", $text);
		
		$keywords = array();
		
		foreach ($plodes as $word)
		{
			$word = trim($word); 
			
			if (strlen($word) < 5)
				continue;
			
			if (isset($keywords[$word]) == false)
				$keywords[$word] = 0;
			
			$keywords[$word]++;
		}
		
		arsort($keywords);
		$keywords = array_slice($keywords, 0, $limit);
		
		return $keywords;
		
	}



	static function extractResources($text)
	{
	
		$return = array();
		
		// Youtube vidoes
		preg_match_all('/(http|https)\:\/\/www\.youtube\.com\/watch\?v=(\w+)/', $text, $matches);
		$matches = (self::reformFinds($matches)); 
	
		foreach ($matches as $key => $match)
		{
			$return['youtube'][$key]["source"] = $match[0];
			$return['youtube'][$key]["full"] = $match[0];
			$return['youtube'][$key]["protocol"] = $match[1];
			$return['youtube'][$key]["id"] = $match[2];
		}
	
	
		// Websites
		preg_match_all('/(http|https|ftp):\/\/(\S+\.\S+)/', $text, $matches);
		
	
		$matches = (self::reformFinds($matches)); 

		foreach ($matches as $key => $match)
		{
			if (stripos($match[0], "youtube.com/watch") !== false)
				continue;
		
		
			$return['website_absolute'][$key]["source"] = $match[0];
			$return['website_absolute'][$key]["full"] = $match[0];
			$return['website_absolute'][$key]["protocol"] = $match[1];
			$return['website_absolute'][$key]["domain"] = $match[2];
		}

		// Detected Websites
		preg_match_all('/\s+([Ww]+\.\w+\.\w+)/', $text, $matches);
		
	
		$matches = (self::reformFinds($matches)); 

		foreach ($matches as $key => $match)
		{
			$return['website_absolute'][$key]["source"] = trim($match[0]);
			$return['website_absolute'][$key]["full"] = "http://".$match[1];
			$return['website_absolute'][$key]["protocol"] = "";
			$return['website_absolute'][$key]["domain"] = $match[1];
		}

		// Detected Emails
		preg_match_all('/\s+(([\w\.-]+)\@([\w\.-]+))/', $text, $matches);
		
		$matches = (self::reformFinds($matches)); 

		foreach ($matches as $key => $match)
		{
			$return['email'][$key]["source"] = trim($match[0]);
			$return['email'][$key]["full"] = $match[1];
			$return['email'][$key]["username"] = $match[2];
			$return['email'][$key]["domain"] = $match[3];
		}
		
		
		// Phone numbers
		//preg_match_all('/[\((\d{3})\)(\s*)|(\d{3})[\.|-]](\d{3})[\.|-](\d{4})/', $text, $matches);
		preg_match_all('/(\d{3})[\.|-| ](\d{3})[\.|-| ](\d{4})/', $text, $matches);
		
		$matches = (self::reformFinds($matches)); 
	
		foreach ($matches as $key => $match)
		{
			$return['phone'][$key]["source"] = $match[0];
			$return['phone'][$key]["full"] = $match[0];
			$return['phone'][$key]["areacode"] = $match[1];
			$return['phone'][$key]["number"] = $match[2]." ".$match[3];
		}

	
		return $return;
	
	}
	
	
	static function reformFinds($matches)
	{
		$new = array();
	
		foreach ($matches as $key => $match)
		foreach ($match as $subkey => $submatch)
		{
			$submatch = trim(rtrim($submatch, "."));
			$new[$subkey][$key] = $submatch;
		}
		
		return $new;
	
	}
	
	
}