<?php


class QueryBuilder
{

	function setWhere($value)
	{
		$this->where[] = $value;
	}


	function setOrder($value)
	{
		$this->order[] = $value;
	}

	function setHaving($value)
	{
		$this->having[] = $value;
	}
	


	function setFrom($value)
	{
		$this->from[] = $value;
	}



	function setSelect($value)
	{
		$this->select[] = $value;
	}

	
	function build()
	{
		if (isset($this->select))
			$v = "\nSELECT " . implode(", \n   ", $this->select);
		else
			$v = "\nSELECT *";

		if (isset($this->from))
			$v .= " \nFROM " . implode(", \n   ", $this->from);
	
		if (isset($this->where))
			$v .= " \nWHERE " . implode(" \n   AND ", $this->where);	

		if (isset($this->having))
			$v .= " \nHAVING " . implode(" \n   AND ", $this->having);	
			
		return $v;
	}
	
	
}

