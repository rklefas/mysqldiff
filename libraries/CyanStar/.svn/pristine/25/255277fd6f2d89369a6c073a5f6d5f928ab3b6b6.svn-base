<?php


class RandomTextGenerator
{
	private $dictionary;


	function __construct($fileOrString)
	{
		if (is_file($fileOrString))
			$string = file_get_contents($fileOrString);
		else
			$string = $fileOrString;
			
			
		$this->dictionary = explode(" ", $string);
	}

	
	function words($min, $max = 0)
	{
		$dictionaryCeiling = count($this->dictionary) - 1;		
		$numberOfWords = empty($max) ? $min : mt_rand($min, $max);
		$tString = "";
		
		for ($ax = 0; $ax < $numberOfWords; $ax++)
		{
			$randomIndex = mt_rand(0, $dictionaryCeiling);
			
			$tString .= $this->dictionary[$randomIndex]." ";
		}
	
		return trim($tString);
	}
	
	
	
	function enum($values)
	{
		$rIndex = mt_rand(0, count($values) - 1);
		return $values[$rIndex];
	}
	
	
	
	function db_date()
	{
		return mt_rand(1800, date('y'))."-".mt_rand(1, 12)."-".mt_rand(1, 28);
	}
	
	function db_datetime()
	{
		return $this->db_date() . " 00:00:00";
	}
	
	



}