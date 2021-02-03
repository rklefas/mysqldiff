<?php


class MemoryCore
{
	
	static function read($key)
	{
		return self::access(__FUNCTION__, $key);
	}

	static function write($key, $value)
	{
		return self::access(__FUNCTION__, key, $value);
	}

	static function delete($key)
	{
		return self::access(__FUNCTION__, $key);
	}
	
	static function exists($key)
	{
		return self::access(__FUNCTION__, $key);
	}
	

	static private function access($operation, $key, $value = null)
	{
		static $memory;
		
		if ($operation == 'exists' && isset($memory[$key]))
			return true;
		else if ($operation == 'read' && isset($memory[$key]))
			return $memory[$key];
		else if ($operation == 'write' && $value != null)
		{
			$memory[$key] = $value;
			return true;
		}
		else if ($operation == 'delete' && isset($memory[$key]))
		{
			unset($memory[$key]);
			return true;
		}
			
		throw new Exception('Operation: '.$operation.', Key: '.$key);
	}

}