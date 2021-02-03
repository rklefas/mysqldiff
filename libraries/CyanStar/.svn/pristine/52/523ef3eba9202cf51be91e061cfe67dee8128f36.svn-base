<?php

abstract class DBO
{

	public $info;
	public $errorString;
	public $errorNumber;
	
	protected $errorHandling;
	protected $halt;
	protected $lastQuery;
	public $credentials;

	protected $objectQuote;
	protected $dataQuote;
	
	

	function castAsArray($inputs)
	{
		if (is_array($inputs))
			return $inputs;
		else if (is_object($inputs))
			return get_object_vars($inputs);
	
		return array();
	}
	
	function setErrorHandling($mode)
	{
		$this->errorHandling = $mode;
	
	
	}
	
	

	public function haltOnError($v)
	{
		if ($v)
			$this->errorHandling = "halt";
		else
			$this->errorHandling = "";
	}
	
	

	
	public function getQuery()
	{
		return $this->lastQuery;
	}
	
	
	
	
	
	
	public function returnSuccess($query)
	{
		if ($this->query($query))
			return true;
		
		return false;
	}
	
	public function returnResultObject($query)
	{
		return $this->query($query);
	}
	
	
	
	
	
	public function returnAssocRow($q)
	{
		return $this->fetchResult($q, true, false);
	}
	
	public function returnObjectRow($q)
	{
		return $this->fetchResult($q, true, true);
	}

	public function returnAssocTable($q)
	{
		return $this->fetchResult($q, false, false);
	}
	
	public function returnObjectTable($q)
	{
		return $this->fetchResult($q, false, true);
	}
	

	public function returnResult($q)
	{
		$row = $this->fetchResult($q, true);
		
		if ($row == false)
			return null;
		
		$vars = $row;
		
		if ($vars !== false && count($vars) > 0)
		{
			$keys = array_keys($vars);
			
			if (count($keys) > 0)
				return $vars[$keys[0]];
		}

		return null;
	}
	
	
	public function returnColumn($q)
	{
		$v = $this->fetchResult($q, false);
		$vals = array();
		
		if (isset($v[0]))
		{
			$allKeys = array_keys($v[0]);
			
			if (isset($allKeys[0]))
			{			
				if (count($v) > 0)
					foreach ($v as $row)
					{
						$vals[] = $row[$allKeys[0]];					
					}
			}
		}
		
		return $vals;
	}
	
	
	
	
	
	
	
	
	function startInsertStack($table, $stackSize = 20)
	{
		$this->insert_stack = array();	
		$this->insert_stack_table = $table;
		$this->insert_stack_size = $stackSize;
	
	}
	
	
	
	function stackedInsert($escapedData = array(), $unescapedData = array())
	{
		$escapedData = $this->castAsArray($escapedData);
		$unescapedData = $this->castAsArray($unescapedData);

		$this->insert_stack[] = array($escapedData, $unescapedData);
		
		if ($this->insert_stack_size)
			if (count($this->insert_stack) >= $this->insert_stack_size)
				$this->releaseInsertStack();
	}
	
	
	function releaseInsertStack()
	{
	
		foreach ($this->insert_stack as $item)
		{
			$escapedData = $item[0];
			$unescapedData = $item[1];
			
			$Ecols = array_keys($escapedData);
			$Ucols = array_keys($unescapedData);
			
			foreach ($Ecols as $key => $datum)
				$Ecols[$key] = $this->objectQuote.($datum).$this->objectQuote;
			
			foreach ($Ucols as $key => $datum)
				$Ucols[$key] = $this->objectQuote.($datum).$this->objectQuote;
				
			foreach ($escapedData as $key => $datum)
				$escapedData[$key] = $this->dataQuote.$this->escapeData($datum).$this->dataQuote;

			$cols = array_merge($Ecols, $Ucols);
			$data = array_merge($escapedData, $unescapedData);
			
			$values[] = "(".implode(", ", $data).")";
		}
		
		$values = implode(", ", $values);

		$this->insert_stack = array();
		
//		echo ("INSERT INTO $this->insert_stack_table (".implode(", ", $cols).") VALUES ".$values) . "<br />";
		return $this->returnAffected("INSERT INTO $this->insert_stack_table (".implode(", ", $cols).") VALUES ".$values);
	}
	
	



	
	
}
