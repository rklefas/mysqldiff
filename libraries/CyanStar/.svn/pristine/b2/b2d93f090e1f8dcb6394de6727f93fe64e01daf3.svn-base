<?php

class PartitionedSession
{
	private $partition_name;


	function __construct($name = 'default', $loadSessionID = null)
	{
		$this->partition_name = $name;
		
		if (session_id() == "")
		{
			if ($loadSessionID)
				session_id($loadSessionID);
		
			session_name(base64_encode(__FILE__));
			session_start();
			$_SESSION['system'] = base64_encode(__FILE__);
		}
	}
	
	
	function id()
	{
		return session_id();
	}
	

	function get($key = null, $default = null)
	{
		if (isset($_SESSION['userspace'][$this->partition_name]))
		{
			if ($key == null)
				return $_SESSION['userspace'][$this->partition_name];
			else if (isset($_SESSION['userspace'][$this->partition_name][$key]))
				return $_SESSION['userspace'][$this->partition_name][$key];
		}
		
		return $default;
	}
	
	
	function dump($default = null)
	{
		if (isset($_SESSION['userspace'][$this->partition_name]))
		{
			return $_SESSION['userspace'][$this->partition_name];
		}
		
		return $default;
	}
	
	function set($key, $value)
	{
		$_SESSION['userspace'][$this->partition_name][$key] = $value;		
		return true;
	}


	function remove($key)
	{
		unset($_SESSION['userspace'][$this->partition_name][$key]);
		return true;
	}
	

	function clear()
	{
		unset($_SESSION['userspace'][$this->partition_name]);
		return true;
	}
	
	
	function get_and_remove($key = null, $default = null)
	{
		$return = self::get($key, $default);
		self::remove($key);
		
		return $return;
	}
	
	
}


