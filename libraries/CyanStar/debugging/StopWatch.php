<?php


class StopWatch
{

	static private function func($key, $cmd)
	{
		static $times;
		
		if ($cmd == 'start')
		{
			$times[$key] = microtime(true);
			return true;
		}
		
		if ($cmd == 'stop')
		{
			$times[$key] = microtime(true) - $times[$key];
			return true;
		}
		
		if ($cmd == 'get')
		{
			return $times[$key];
		}
		
		else
		{
			return $times;
		}
		
	}

	static function start($key)
	{
		return self::func($key, __FUNCTION__);
	}

	static function stop($key)
	{
		return self::func($key, __FUNCTION__);
	}
	
	static function get($key)
	{
		return self::func($key, __FUNCTION__);
	}
	
	static function all()
	{
		return self::func(null, __FUNCTION__);
	}
}