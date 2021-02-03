<?php

class FastGetBuilder
{

	static function alter($name, $value, $string = null)
	{
		$gets = new GetBuilder($string);
		$gets->alter($name, $value);
		return "?".$gets->dumpGetString();
	}

	static function get($name, $string = null)
	{
		$gets = new GetBuilder($string);
		return $gets->get($name);
	}

}
