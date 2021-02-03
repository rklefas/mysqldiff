<?php

class GetBuilder
{

	// Associative array containing the GET string key, value pairs
	private $getSegments;
	private $file;

	public function __construct($getString = null)
	{	
		if ($getString === null)
			$getString = $_SERVER['QUERY_STRING'];
			
		$qpos = strpos($getString, "?");
		$epos = strpos($getString, "=");
	
		if ($qpos !== false)
		{
			$this->file = substr($getString, 0, $qpos);
			$getString = substr($getString, $qpos + 1);
		}
		else if ($epos !== false)
		{
			$this->file = "";
			$getString = $getString;
		}
		else
		{
			$this->file = $getString;
			$getString = null;
		}
			
			
		if ($getString)
			$parameters = explode("&", $getString);
		else
			$parameters = array();
		
		
		$newArray = array();
		
		if (is_array($parameters) && count($parameters) > 0)
		{
			foreach ($parameters as $para)
			{
				$temp = explode("=", $para);
				
				if (count($temp) > 1)
					$newArray[$temp[0]] = urldecode($temp[1]);
				else
					$newArray[$temp[0]] = null;
			}
		}
		
		$this->getSegments = $newArray;
	}
	
	
	
	public function delete($name)
	{
		unset($this->getSegments[$name]);
	}
	
	
	public function alter($name, $value)
	{
		$this->getSegments[$name] = $value;
	}
	
	
	public function get($name)
	{
		if (isset($this->getSegments[$name]))
			return $this->getSegments[$name];
			
		return null;
	}
	

	public function dumpGetString()
	{
		$finalString = "";
	
		foreach ($this->getSegments as $key =>$val)
		{
			if (strlen($finalString) == 0)
				$finalString = $key;
			else
				$finalString .= "&" . $key;
				
			if (strlen($val) > 0)
				$finalString .= "=" . urlencode($val);
		}
	
		return $finalString;
	}
	
	
	public function dumpScriptString()
	{
		return $this->file . '?' . $this->dumpGetString();
	}


}
