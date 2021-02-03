<?php

class URI
{

	static function postVars($names = null)
	{
		$vx = ($_POST);

		if ($names === null)
			return $vx;
			
		$return = Arrays::whiteList($vx, $names);
		
		return $return;
	}


    
    static function postVar($name, $default = null)
    {        
		$vx = ($_POST);

		if(isset($vx[$name]))
			return $vx[$name];
			
		return $default;
    }
	
	static function getVars($names = null)
	{
		$vx = ($_GET);

		if ($names === null)
			return $vx;
			
		$return = Arrays::whiteList($vx, $names);
		
		return $return;
	}


    
    static function getVar($name, $default = null)
    {        
		$vx = ($_GET);

		if(isset($vx[$name]))
			return $vx[$name];
			
		return $default;
    }
		
	
}
