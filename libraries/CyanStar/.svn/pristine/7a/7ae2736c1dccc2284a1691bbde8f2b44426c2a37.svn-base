<?php

class DBO_Postgres extends DBO
{

	public function __construct($connstring)
	{
		$this->info = array();
		$this->returnAsObject = true;
	

		$this->connection = pg_connect( $connstring );
		
		$this->objectQuote = '"';
		$this->dataQuote = "'";
		
		// if ($this->connection->connect_errno != null) 
			// $this->appendLog( __CLASS__ ." connection failed: " .  $this->connection->connect_errno);
	}

	protected function appendLog($info)
	{

		$this->info[] = $info;
	}
	

	public function returnAffected($query)
	{
		if ($this->query($query))
		{
			return 1;
		}
		
		return false;
	}
	
	
	function escapeData($d)
	{
		return pg_escape_string($this->connection, $d);
	}
	
	
	
	protected function query($sql)
	{
		$this->lastQuery = $sql;
		$return = pg_query($this->connection, $sql);
		
		if ($e = pg_last_error($this->connection))
		{
//			exit($e);
			$this->appendLog($e."  ".$sql);
			return false;
		}
		else
		{
			$this->appendLog($sql);
			return $return;
		}
	}
	
	
	function insert_returning($table, $returnColumns = "*", $escapedData = null, $unescapedData = null)
	{
		return $this->returnAssocRow($this->statement_insert($table, $escapedData, $unescapedData)." RETURNING $returnColumns");
	}

	
	
	
	protected function fetchResult($q, $single, $asObject = false)
	{
		$result = $this->returnResultObject($q);
		
		if (!$result)
			return false;
			
			
		$rows=array();
		
		if ($asObject)
		{
			while($row = pg_fetch_object($result))
				$rows[] = $row;
		}
		else
		{
			while($row = pg_fetch_assoc($result))
				$rows[] = $row;
		}
	
		if ($single && isset($rows[0]))
		{
			$rows = $rows[0];
		}
		
		return $rows;
	}


	function insert($table, $escapedData = null, $unescapedData = null, $specialType = null)
	{
		return $this->returnAffected(SQL_Syntax_PostgreSQL::insert($table, $escapedData, $unescapedData));
	}
	
	
	function update($table, $where = null, $escapedData = null, $unescapedData = null)
	{
		return $this->returnAffected(SQL_Syntax_PostgreSQL::update($table, $where, $escapedData, $unescapedData));
	}
	
	
	function delete($table, $where = null)
	{
		return $this->returnAffected(SQL_Syntax_PostgreSQL::delete($table, $where));
	}	
}