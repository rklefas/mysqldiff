<?php


class SQL_Syntax_MySQL extends SQL_Syntax
{

	static function escape_data($data)
	{
		if ($data === null)
			return 'NULL';

		return "'".addslashes($data)."'";
	}


	static function escape_object($object)
	{
		$object = str_replace("`", "", $object);
		$object = str_replace(".", "`.`", $object);
		return "`$object`";
	}


	static function where($escapedData)
	{
		if (is_string($escapedData))
			return $escapedData;
		

		foreach ($escapedData as $key => $val)
		{
			if ($val === null)
				$newD[] = self::escape_object($key)." IS NULL";
			else
				$newD[] = self::escape_object($key)." = ".self::escape_data($val);
		}
	
		return implode(" AND ", $newD);
	
	}
	
	
	
	static function update($table, $where = null, $escapedData = null, $unescapedData = null)
	{
		$escapedData = self::castAsArray($escapedData);
		$unescapedData = self::castAsArray($unescapedData);

		$newD = array();
	
		if ($escapedData)
			foreach ($escapedData as $key => $val)
				$newD[] = self::escape_object($key)." = ".self::escape_data($val);
	
		if ($unescapedData)
			foreach ($unescapedData as $key => $val)
				$newD[] = self::escape_object($key)." = ".($val);

		return ("UPDATE ".self::escape_object($table)." SET ".implode(", ", $newD) ." WHERE ".self::where($where));	
	}
	
	
	static function delete($table, $where = null)
	{
		$query = "DELETE FROM ".self::escape_object($table);
		
		if ($where)
			$query .= " WHERE ".self::where($where);
			
		return ($query);
	}
	
	
	static function pagination($page, $pageSize)
	{	
		$start = empty($page) ? 0 : ($page - 1) * $pageSize;	
		
		return "LIMIT $pageSize OFFSET $start";
	}
	
	

	static function insert($table, $escapedData = null, $unescapedData = null)
	{
		$escapedData = self::castAsArray($escapedData);
		$unescapedData = self::castAsArray($unescapedData);
	
		$cols = array_merge(array_keys($escapedData), array_keys($unescapedData));
		
		foreach ($cols as $key => $datum)
			$cols[$key] = self::escape_object($datum);
			
		foreach ($escapedData as $key => $datum)
			$escapedData[$key] = self::escape_data($datum);

		$data = array_merge($escapedData, $unescapedData);
		
		$values = "(".implode(", ", $data).")";

		return ("INSERT INTO ".self::escape_object($table)." (".implode(", ", $cols).") VALUES ".$values);
	}
	
	
	static function paginated_query($sql, $page, $pageSize)
	{
		if (is_numeric($pageSize) == false)
		{
			trigger_error('$pageSize must be a number');
			return "";
		}

		if (empty($sql))
		{
			trigger_error("Cannot add pagination to an empty query.");
			return "";
		}
		
		return "SELECT SQL_CALC_FOUND_ROWS * FROM (".(trim($sql)).") as derived ".self::pagination($page, $pageSize);
	}


}