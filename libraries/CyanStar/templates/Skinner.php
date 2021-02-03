<?php

Class Skinner
{
	private $valueStack;
	private $__config;
	private $__self;
	
	
	public function __construct()
	{
		$this->valueStack = new stdClass;

		$this->__config = array();
		$this->__config["template_path"] = dirname(__FILE__).DIRECTORY_SEPARATOR.'tpl';
		$this->__config["debug"] = false;
		$this->__config["fallback_template"] = null;

		$this->__self = array();
		$this->__self['version'] = "1.0";
		$this->__self['date'] = date('F j, Y', strtotime('2010-06-10'));
		$this->__self['install_path'] = __FILE__;
	}
	
	
	public function setConfig($option, $value)
	{
		$this->__config[$option] = $value;
	}
	
	
	public function getConfig($option = null)
	{
	
		if ($option != null)
			return $this->__config[$option];
	
		echo '
		
		<style type="text/css">
		
			table, td, th
			{
				border: 1px inset pink;
				padding: 0.4em;
			
			}
			
			th {
				text-align: right; 
			}
		
		
		
		</style>
		
		
		
		<table style="border: 1px gray solid; ">
			<thead style="background-color: SkyBlue;">
				<tr>
					<th colspan="2">'.__CLASS__.' Template Engine</th>
				</tr>
			</thead>
			<tbody>
		';
		
		echo '
			<tr>
				<th colspan="2">About</th>
			</tr>
		';
		
		
		foreach ($this->__self as $option => $val)
		{
			echo '<tr><th>'.$option.'</th><td>'.$val.'</td></tr>';
		}
		
		
		echo '
			<tr>
				<th colspan="2">Configuration</th>
			</tr>
		';

		foreach ($this->__config as $option => $val)
		{
			echo '<tr><th>'.$option.'</th><td>'.$val.'</td></tr>';
		}
		

		echo '
			</tbody>
		</table>';
		
		
		
	
	}
	

	
	function is($name)
	{
		return property_exists($this->valueStack, $name);
	}
	
	function has($name)
	{
		return !empty($this->valueStack->$name);
	}

	public function assign($name, $value)
	{
		$this->valueStack->$name = $value;
	}

	public function display($template, $maxdepth = -1, $returnAsString = false)
	{
		if ($returnAsString)
			return $this->recurseInclude($template, $maxdepth);
		else
			echo $this->recurseInclude($template, $maxdepth);
	}

	public function render($template, $maxdepth = -1)
	{
		return $this->recurseInclude($template, $maxdepth);
	}

	public function get($name, $default = null)
	{
		if (property_exists($this->valueStack, $name))
			return $this->valueStack->$name;
		
		return $default;
	}
	
	public function need($name)
	{
		if (property_exists($this->valueStack, $name))
			return $this->valueStack->$name;
		
		trigger_error('Variable \''.$name.'\' is required, but undefined.');
	}

	
	
	public function escape($name)
	{
		return htmlentities($this->get($name));
	}
	
	
	private function recurseInclude($template, $maximumDepth = -1, $childContent = null, $calls = 0)
	{
		$fpath = $this->fetchTemplate($template);
		
		if ($fpath == null)
			if ($this->getConfig('fallback_template'))
				$fpath = $this->fetchTemplate($this->getConfig('fallback_template'));
		
		if ($fpath == null)
			trigger_error($template.' could not be found', E_USER_WARNING);
		
		ob_start();	
		include ( $fpath );
		$view = ob_get_contents();
		ob_end_clean();
		
		
		if ($this->getConfig('debug'))
		{
			// Don't add the HTML comments if they will interfere with the DOCTYPE
		
			if (strtoupper(substr($view, 0, 9)) != "<!DOCTYPE")
			{
				$view = "\n<!-- Opening: '$template' | Recursion Level: $calls -->\n".$view;
			}
			
			$view = $view."\n<!-- Closing: '$template' -->\n";

		}
		
		if ($childContent != null)
		{
			// Escape the dollar signs so they aren't parsed as back-references
			$childContent = str_replace('$', '\$', $childContent);
			
			$replaceCount = 0;
			$view = preg_replace('/<!--(\s+)\[reserved block](\s+)-->/', $childContent, $view, -1, $replaceCount);
			
	//		$view = str_replace('<!-- [reserved block] -->', $childContent, $view);
			
			if (empty($replaceCount))
				trigger_error('Template reservation block not found in '.$template, E_USER_WARNING);
		}
		
		
		$line = null;
		
		foreach (file($fpath) as $linex)
		{
			$linet = trim($linex);
			
			if (strlen($linet) > 0)
			{
				$line = $linet;
				break;
			}
		}
		

		$parent = $this->getAttribute("src", $line);
				
		if ($parent == null || $maximumDepth == $calls)
			return ($view);
		else
			return $this->recurseInclude($parent, $maximumDepth, $view, ++$calls);
	}
	
	
	private function fetchTemplate($template)
	{
		$paths = $this->getConfig('template_path');
		
		if (is_file($template))
			return $template;

		if (is_string($paths))
			$paths = array( $paths );
		
		foreach ($paths as $path)
		{
			if (!is_dir($path))
				trigger_error($path . " is not a folder.", E_USER_WARNING);

			$filepath = $path.DIRECTORY_SEPARATOR.$template;
			$filepath = preg_replace('|/+|', '/', $filepath);
			
			if (is_file($filepath.".php"))
				return $filepath.".php";
			else if (is_file($filepath.".tpl"))
				return $filepath.".tpl";
			else if (is_file($filepath))
				return $filepath;
		}
		return null;
	}
	
	
	private function getAttribute($findibute, $line)
	{
		$matches = array();
		$count = preg_match("/<!--(\s+)\[extend (.*?)\](\s+)-->/", $line, $matches);

		if ($count)
		{
			$attributes = trim($matches[2]);
			$attributeMatches = array();
			preg_match("/(.*)".$findibute."=\"(.*?)\"(.*)/", $attributes, $attributeMatches);
			
			if ( $attributeMatches[2] )
				return $attributeMatches[2];
			
			trigger_error('The extend tag is malformed.  Unable to find attribute "'.$findibute.'".', E_USER_WARNING);
		}
		
		return null;
	}
	
	
}