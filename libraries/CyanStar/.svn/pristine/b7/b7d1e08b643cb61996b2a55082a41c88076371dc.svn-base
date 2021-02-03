<?php


class DataTypeConversion
{

	/* bool/array decode_xml ( string $input [ , callback $callback ] )
	 * Unserializes an XML string, returning a multi-dimensional associative array, optionally runs a callback on all non-array data
	 * Returns false on all failure
	 * Notes:
		* Root XML tags are stripped
		* Due to its recursive nature, decode_xml() will also support SimpleXMLElement objects and arrays as input
		* Uses simplexml_load_string() for XML parsing, see SimpleXML documentation for more info
	 */
	static function decode_xml($input, $callback = null, $recurse = 0)
	{
		// Get input, loading an xml string with simplexml if its the top level of recursion
		$data = ((!$recurse) && is_string($input))? simplexml_load_string($input): $input;
		
		// Convert SimpleXMLElements to array
		if ( $data instanceof SimpleXMLElement )
			$data = (array) $data;

		if (is_array($data) )
		{
			// Make sure NOT to create useless, empty arrays
			
			if (count($data) == 0)
				$data = '';
			else if (count($data) == 1 && isset($data[0]))
				$data = $data[0];
			else
			{
				foreach ($data as &$item) 
					$item = self::decode_xml($item, $callback, $recurse + 1);
			}
		}	
		// Recurse into arrays
		// Run callback and return
		return (!is_array($data) && is_callable($callback))? call_user_func($callback, $data): $data;
	}
	
	
	static function encode_xml($to_encode, $root = 'xml', $encoding = 'UTF-8', $_level = 0)
	{
		$xml = '';
	 
		// If the given content is an object, convert it to an array so that we can loop through all the values
		if (is_object($to_encode))
		{
			$to_encode = get_object_vars($to_encode);
		}
		
		
		// Loop through each value in the array and add it to the current level if it is a single value, or make a
		// recursive call and indent the level by one if the value contains a collection of sub values
		
		$tArray = array();
		
		if ($_level == 0)
		{
			$tArray[$root] = $to_encode;
		}
		else
		{
			$tArray = $to_encode;
		}
		
		
		foreach ($tArray as $key => $value)
		{
			if (is_numeric($key))			
			{
				// make string key...				
				$key = "{$root}_{$key}";
			} 	
				
			$indent = str_repeat("\t", $_level);
			
			if ((is_array($value) || is_object($value)) && count($value) > 0)
			{
				$xml .= $indent."<{$key}>\n".self::encode_xml($value, $key, $encoding, $_level + 1).$indent."</{$key}>\n";
			}
			else
			{
				// Convert entities to an appropriate form so that the XML remains valid, but DO NOT trim any whitespace
				// because we want to preserve the data exactly as it is.
				
				if ((is_array($value) || is_object($value)) && count($value) == 0)
					$value = null;
				else 
					$value = htmlentities($value);
					
				$xml .= $indent."<{$key}>{$value}</{$key}>\n";
			}
		}
		// If this is the first call, then start with a new XML tag
		// Close the XML tag if this is the last recursive call
		return $_level == 0 ? "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>\n".trim($xml) : $xml;
	}


	
	static function ArrayToObject($data) 
	{
	   if(!is_array($data)) return $data;
	   
	   $object = new stdClass();
	   if (is_array($data) && count($data) > 0) 
	   {
	      foreach ($data as $name=>$value) 
	      {
	         $name = (trim($name));
	         if (!empty($name)) 
	         {
	            $object->$name = self::ArrayToObject($value);
	         }
	      }
	   }
	   return $object;
	}


	static function ObjectToArray($data)
	{
	   if(!is_object($data) && !is_array($data)) return $data;
	
	   if(is_object($data)) $data = get_object_vars($data);

		foreach ($data as $key => $val)
		{
			$data[$key] = self::ObjectToArray($val);
		}

		return $data; 
	}

	
	
	
	static function arrayToURL($arr)
	{
		$v = array();
	
		foreach ($arr as $key => $opt)
		{
			$v[] = $key."=".urlencode($opt);
		}
	
		return implode("&", $v);
	}

	
	static function urlToArray($url)
	{
		$finalAssoc = array();
		
		if (strlen($url) > 0)
		{
			$firstStep = explode("&", $url);

			foreach ($firstStep as $param)
			{
				$breaks = explode("=", $param);
				$val = (isset($breaks[1]))?$breaks[1]:'';
				$finalAssoc[$breaks[0]] = urldecode($val);
			}
		}
		
		return $finalAssoc;
	}

	
	
	static function isJSON($v)
	{
		if (is_string($v))
		{
			$return = json_decode($v);
			
			if (is_array($return) or is_object($return))
				return true;

		}
		
		return false;
	}
	
	
	
	
}
