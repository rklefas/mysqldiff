<?php

class DBO_MySQLi extends DBO
{


	public function __construct($host, $user, $pass, $data = null)
	{
		$this->info = array();
		$this->returnAsObject = true;
	
		$this->credentials		  = new stdclass;
		$this->credentials->host  = $host;
		$this->credentials->user  = $user;
		$this->credentials->pass  = $pass;
		$this->credentials->db    = $data;

		if ($data)
			$this->connection = new mysqli( $host, $user, $pass, $data );
		else
			$this->connection = new mysqli( $host, $user, $pass );
		
		$this->objectQuote = "`";
		$this->dataQuote = "'";
		
		if ($this->connection->connect_errno != null) 
			$this->appendLog( __CLASS__ ." connection failed: " .  $this->connection->connect_errno);
	}

	
	function escapeData($d)
	{
		return addslashes($d);
	}

	

	protected function query($sql)
	{
		$this->lastQuery = $sql;
		$start = microtime(true);
		$return = $this->connection->query($sql);
		$duration = microtime(true) - $start;
		$this->errorNumber = $this->connection->errno;
		$this->errorString = $this->connection->error;
		$this->appendLog($sql, $duration);
		
		if ($this->connection->error == null)
		{
			return $return;
		}
		else
		{
			return false;
		}
	}
	
	
	
	
	public function lastInsert()
	{
		return $this->returnResult("SELECT LAST_INSERT_ID()");
	}
	
	
	
	public function returnAffected($query)
	{
		if ($this->query($query))
		{
			return $this->connection->affected_rows;
		}
		
		return false;
	}

	
	

	public function strictMode($v)
	{
		if ($v)
			return $this->returnSuccess("SET SQL_MODE='TRADITIONAL'");
		else
			return $this->returnSuccess("SET SQL_MODE=''");
	}
	
	
	protected function fetchResult($q, $single, $asObject = false)
	{
		$result = $this->returnResultObject($q);
		
		if (!$result)
			return false;

		$rows=array();
		
		if ($asObject)
		{
			if ($single)
			{
				$rows = $result->fetch_object();
			}
			else
			{
				while($row = $result->fetch_object())
					$rows[] = $row;
			}
		}
		else
		{
			if ($single)
			{
				$rows = $result->fetch_assoc();
			}
			else
			{
				while($row = $result->fetch_assoc())
					$rows[] = $row;
			}
		}
		
		$result->close();
		return $rows;
	}
	
	

	
	


	protected function appendLog($info, $duration = null)
	{
		$e = array( $info );
		
	
		if ($this->connection->error != null)
			$e[] = "ERROR (".$this->connection->errno."):  ".$this->connection->error;


			
		$new = implode(" // ", $e);

		
		$sql = $this->lastQuery;

		
		/*
//		if (stripos($sql, 'delete') === 0 || stripos($sql, 'update') === 0 || stripos($sql, 'insert') === 0 )
		{
			$sPiece = array();
			
			$sPiece[] = "SQL: $sql ";

			// if (mysql_error( $this->_resource ) != '')
				// $sPiece[] =  'ERROR: '.mysql_error( $this->_resource );

			// if (stripos($sql, 'delete') === 0 || stripos($sql, 'update') === 0 || stripos($sql, 'insert') === 0)
				// $sPiece[] = "AFFECT: ".$this->getAffectedRows()."\n";
			// else
				// $sPiece[] = "RETRIEVE: ".$this->getNumRows()."\n";

			$stringToWrite = implode("\n", $sPiece)."\n";

			$path = '_logger.sql';
			$fh = fopen($path, 'a+');
			$written = fwrite($fh, $stringToWrite);
			fclose($fh);
		}
		*/

		
		if (count($e) > 1)
		{
			if ($this->errorHandling == "halt")
				exit ('<pre>The ' . __CLASS__ . ' database access layer has halted due to the following error: '."\n\n". $new."\n\n".print_r(debug_backtrace(), true)."</pre>");
			elseif ($this->errorHandling == "trigger error" || $this->errorHandling == 'trigger_error')
				trigger_error($new);
			elseif ($this->errorHandling == "throw exception")
			{
				throw new Exception($e);
			}
			else
			{
				$this->loggedErrors[] = $e;
			}
		}	
	
		$this->info['query'][] = $new;
		$this->info['duration'][] = $duration;
		asort($this->info['duration']);
	
	}
	
	
	
	function insert($table, $escapedData = null, $unescapedData = null, $specialType = null)
	{
		return $this->returnAffected(SQL_Syntax_MySQL::insert($table, $escapedData, $unescapedData));
	}
	
	
	function update($table, $where = null, $escapedData = null, $unescapedData = null)
	{
		return $this->returnAffected(SQL_Syntax_MySQL::update($table, $where, $escapedData, $unescapedData));
	}
	
	
	function delete($table, $where = null)
	{
		return $this->returnAffected(SQL_Syntax_MySQL::delete($table, $where));
	}
	


	function insert_on_key_update($table, $updateCondition, $escapedData = null, $unescapedData = null)
	{
		$escapedData = $this->castAsArray($escapedData);
		$unescapedData = $this->castAsArray($unescapedData);
	
		$cols = array_merge(array_keys($escapedData), array_keys($unescapedData));
		
		foreach ($cols as $key => $datum)
			$cols[$key] = $this->objectQuote.($datum).$this->objectQuote;
			
		foreach ($escapedData as $key => $datum)
			$escapedData[$key] = $this->dataQuote.addslashes($datum).$this->dataQuote;

		$data = array_merge($escapedData, $unescapedData);
		
		$values = "(".implode(", ", $data).")";
		
		$query = "INSERT INTO $table (".implode(", ", $cols).") VALUES ".$values." ON DUPLICATE KEY UPDATE ".$updateCondition;
		
		//print_pre($query);
		return $this->returnAffected($query);

	}
	
	
	
	function insert_and_return($table, $escapedData = null, $unescapedData = null, $default = array())
	{
		$this->insert($table, $escapedData, $unescapedData);
		
		if ($field = $this->primaryKeyField($table))
		{
			return $this->select($table, SQL_Syntax_MySQL::escape_object($field).' = LAST_INSERT_ID()');
		}
		
		return $default;
	}
	
	
	
	function update_and_return($table, $where = null, $escapedData = null, $unescapedData = null)
	{
		$this->update($table, $where, $escapedData, $unescapedData);
		
		return $this->select($table, $where);	
	}
	
	function upsert($table, $where = null, $escapedData = null, $unescapedData = null)
	{
		$v = $this->update($table, $where, $escapedData, $unescapedData);
		
		if ($v)
		{
			return $v;
		}
		
		$v = $this->insert($table, $escapedData, $unescapedData);
		
		return $v;
	}

	
	function primaryKeyField($table)
	{
	
		$desc = $this->returnAssocTable("DESCRIBE ".SQL_Syntax_MySQL::escape_object($table) );
				
		foreach ($desc as $row)
		{
			if ($row['Key'] == "PRI" && $row['Extra'] == "auto_increment")
			{
				return $row['Field'];
			}
		
		}
	
		return null;
	
	}
	
	
	

	
	function select($table, $where)
	{
	
	
		return $this->returnAssocRow("SELECT * FROM ".SQL_Syntax_MySQL::escape_object($table)." WHERE ".SQL_Syntax_MySQL::where($where) );
	
	
	}
	

	
}