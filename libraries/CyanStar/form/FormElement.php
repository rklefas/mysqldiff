<?php





class FormElement
{
	public $data;
	public $name;
	public $formAttributes;
	public $formErrors;

	function __construct($name, $attributes = null)
	{
		$this->name = $name;
		$this->formAttributes['action'] = null;
		$this->formAttributes['method'] = 'post';
		$this->formErrors = array();

		if ($attributes != null)
			$this->setAttributes($attributes);
	}
	
	
	function addElement($el)
	{
		$this->data[] = $el;
	}
	
	
	public function setAttributes($array)
	{
		$this->formAttributes = array_merge($this->formAttributes, $array);
	}
	
	
	
	function makeAttributeString()
	{
		$s = null;
		
		$arr = $this->formAttributes;
		
		
		if (count($arr))
		{
			foreach ($arr as $key => $val)
				$s[] = $key.'="'.$val.'"';
		
			return " ".implode(" ", $s);
		}
		
		return $s;
	}
	
	
	
	function preloadValues($array)
	{
		foreach ($this->data as $key => $elem)
		{
			$name = $elem->getName();
		
			if (isset($array[$name]))
				$elem->setValue($array[$name]);
		}
		
	}


	
	function validates()
	{
		static $result;
		
		
		if ($result !== true && $result !== false)
		{
			foreach ($this->data as $id => $elem)
			{
				if ($elem->type == "file")
					$elem->checkFiles();
				else
					$elem->checkRequest();

				$this->formErrors = array_merge($this->formErrors, $elem->errors);
			}
			
			$result = !empty($_POST) && empty($this->formErrors);
		}
		
		return $result;
	}
	
	
	function values()
	{
		$arr = array();
	
		foreach ($this->data as $id => $elem)
		{
			$arr[$elem->getName()] = $elem->getValue();
		}
		
		return $arr;
	}
	
	
	
	function isPosted()
	{
		return count($_POST) > 0;
	}
	
	
	
	function returnElements()
	{
		return $this->data;
	}
	
	

}






?>