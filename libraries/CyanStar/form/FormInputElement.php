<?php

class FormInputElement
{

	public $errors;
	public $value;
	public $name;

	
	function __construct()
	{
		$this->value = null;
		$this->name = null;
		$this->type = null;
		$this->heading = null;
		$this->errors = array();
		$this->validations = array();
		
		return $this;
	}
	
	

	function renderInput()
	{
		if ($this->type == "select")
		{
			$return = '<select id="'.$this->activeName.'" name="'.$this->activeName.'">';
		
			foreach ($this->labelPack as $lab => $val)
			{
				if ($this->value == $val)
					$return .= '<option selected value="'.$val.'">'.$lab.'</option>';
				else
					$return .= '<option value="'.$val.'">'.$lab.'</option>';
			}
			
			$return .= "</select>";
		}
		else if ($this->type == "textarea")
		{
			$return = '<textarea id="'.$this->activeName.'" name="'.$this->activeName.'">';
			$return .= $this->value;
			$return .= "</textarea>";
		}
		else if (isset($this->validations['max-length']))
			$return = '<input id="'.$this->activeName.'" maxlength="'.$this->validations['max-length'].'"  type="'.$this->type.'" value="'.$this->value.'" name="'.$this->activeName.'" />';
		else
			$return = '<input id="'.$this->activeName.'" type="'.$this->type.'" value="'.$this->value.'" name="'.$this->activeName.'" />';

		return $return;
	}
	
	
	
	
	function checkFiles()
	{
		if (empty($_FILES))
			return true;
	
		if (!isset($_FILES[$this->activeName]))
		{
			die ($this->activeName." is not a valid FILES index");
		}


		if (isset($this->validations))
			$x = $this->validations;
		else
			return true;

		$heading = empty($this->heading) ? ucfirst($this->activeName).'/'.ucfirst($this->value) : $this->heading;

		if (!empty($_FILES[$this->activeName]['error']))
			$this->errors[] = $heading." has errors";
	}
	
	
	
	
	function checkRequest()
	{

		if (empty($_POST))
			return true;

		if (!isset($_POST[$this->activeName]))
		{
		//	die ($this->activeName." is not a valid REQUEST index");
		}
		else
			$index = $_POST[$this->activeName];

		if (isset($this->validations))
			$x = $this->validations;
		else
			return true;
			
		$heading = empty($this->heading) ? ucfirst($this->activeName).'/'.ucfirst($this->value) : $this->heading;

		if (isset($x['required']))
			if (empty($index))
				$this->errors[] = $heading." is required";
		
		if (isset($x['max-length']))
			if (strlen($index) > $x['max-length'])
				$this->errors[] = $heading." max length of ".$x['max-length']." characters exceeded";

		if (isset($x['min-length']))
			if (strlen($index) < $x['min-length'])
				$this->errors[] = $heading." min length of ".$x['min-length']." characters not met";
			
		if (isset($x['identical']))
			if ($index != $_POST[$x['identical']])
				$this->errors[] = $heading." does not match";

		if (isset($x['field-type']) && !empty($index))
		{
			if ($x['field-type'] == "numeric")
				if (!is_numeric($index))
					$this->errors[] = $heading." must contain only numbers";

			if ($x['field-type'] == "alphanumeric")
				if (!$this->is_alpha_numeric($index))
					$this->errors[] = $heading." must contain only numbers and letters";

			if ($x['field-type'] == "email")
				if (!$this->is_email($index))
					$this->errors[] = $heading." must be a valid email address";
		}

		return count($this->errors) == 0;
	}
	
	function is_email($email)
	{
		return preg_match("/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/",$email);
	}

	function is_alpha_numeric($str)
	{
		return preg_match("/^[A-Za-z0-9 ]+$/",$str);
	}

	function setValue($v)
	{
		if (isset($_FILES[$this->activeName]))
			$this->value = $_FILES[$this->activeName];
		else if (isset($_POST[$this->activeName]))
			$this->value = $_POST[$this->activeName];
		else
			$this->value = $v;
		
		return $this;
	}
	
	
	function setName($v)
	{
		$this->activeName = $v;
		
		$this->setValue(null);
		
		return $this;
	}
	


	function setValidation($property, $value)
	{
		$this->validations[$property] = $value;
		return $this;
	}


	function setDisplay($type, $h = null, $valuesAndLabels = null)
	{
		$valids[] = "hidden";
		$valids[] = "text";
		$valids[] = "password";
		$valids[] = "textarea";
		$valids[] = "select";
		$valids[] = "submit";
		$valids[] = "file";

		if (!in_array($type, $valids))
			die("A display type of " . LibStrings::writtenImplode($valids) . " must be passed");

		$this->type = $type;
		
		if ($h != null)
			$this->heading = $h;
		
		if ($valuesAndLabels != null)
			$this->labelPack = $valuesAndLabels;
		
		return $this;
	}
	
	
	
	function getValue()
	{
		return $this->value;
	}

	function getName()
	{
		return $this->activeName;
	}

}

