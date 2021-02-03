<?php


class SQL_Syntax_PostgreSQL extends SQL_Syntax
{


	static function escapeData($data)
	{
		return "";
	}


	static function escape_object($object)
	{
		return "";
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

		return ("UPDATE ".self::escape_object($table)." SET ".implode(", ", $newD) ." WHERE ".($where));	
	}
	
	
	static function delete($table, $where = null)
	{
		$query = "DELETE FROM ".$table;
		
		if ($where)
			$query .= " WHERE ".($where);
			
		return ($query);
	}
	
	
	static function limit($page, $pageSize)
	{	
		$start = empty($page) ? 0 : ($page - 1) * $pageSize;	
		
		return " LIMIT $pageSize OFFSET $start";
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

	
	
}