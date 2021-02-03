<?php


class ValidationCheck
{

    static function isEmail($email)
    {
        return (preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/", $email) ? true : false);
    }
	
	

	static function is_odd($v)
	{
		return $v % 2 == 1;
	}

	
	
	static function is_email($v)
	{
		$at = strpos($v, "@");
		$dot = strpos($v, ".");
		$space = strpos($v, " ");
		
		if ($space === false)
			if ($at > 0)
				if ($dot > $at + 1)	
					return true;
				
		return false;
	}
    


	static function is_website($v)
	{
		$proto = strpos($v, "http");
		$dot = strpos($v, ".");
		$space = strpos($v, " ");
		
		if ($space === false)
			if ($proto === 0)
				if ($dot > 5)
					return true;
				
		return false;
	}

    
    static function isDate($text)
    {        
        $Stamp = strtotime( $text );
        return ($Stamp) > 0;
    }
	
	static function isNumeric($v)
	{
		return is_numeric($v);
	}

	static function hasValue($v)
	{
		return strlen($v) > 0;
	}
	
	
	static function maxLength($v, $max)
	{
		return strlen($v) <= $max;
	}
	
	
	static function minLength($v, $min)
	{
		return strlen($v) >= $min;
	}
	
	static function fixedLength($v, $len)
	{
		return strlen($v) == $len;
	}
	

}
