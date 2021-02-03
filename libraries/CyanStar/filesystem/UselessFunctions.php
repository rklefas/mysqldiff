<?php

/* Search for unreferenced files */
// This program searches in a directory for any files that are not directly referenced
// by any of the other files in that directory

/* USAGE

/////////////////////////////////

set_time_limit(0);

require "InTheRed/2-0/std_library.php";
require "InTheRed/2-0/echo_library.php";
require "InTheRed/2-0/classes/filesystem/class.filereferencer.php";

$refer = new FileReferencer;
$refer->checkFilesInThisDirectory("sublease");
$refer->checkAgainstThisDirectory("sublease");
$refer->showUnreferencedFiles();

/////////////////////////////////

*/


class UselessFunctions
{
	public $fileList;
	public $uselessFunctions;


	public function __construct()
	{
		$this->fileList = array();
		$this->uselessFunctions = array();
	}

	
	/* Individually gather files to check for */

	public function checkForThisFile($file)
	{
		$this->fileList[] = $file;
	}

	/* Gather files to check for by directory */

	public function checkFilesInThisDirectory($dir)
	{
		if (!is_dir($dir))
			throw new Exception($dir . ' is not a directory!');
	
		$this->fileList = array_merge($this->fileList, fileList($dir));
	}

	
	
	function gatherFunctionUsage()
	{
	
		foreach ($this->fileList as $file)
		{
			$result = $this->retrieveFunctionHeaders($file);
		
			if (!empty($result))
				$this->uselessFunctions[] = $result;
		}
	
		
	}
	

	function retrieveFunctionHeaders($file)
	{
		
	
	
		$lines = file_get_contents($file);
		
		if (!stringContains($lines, '<?'))
			return null;
	
		$matches = null;
		
		
		$matchCount = preg_match_all("/(.*?)function (.*?)\(/", $lines, $matches);
		
		if (empty($matchCount))
			return null;
		
		return $matches;
	}

	
	
	function justFunctions()
	{
		$jf = array();
	
		foreach ($this->uselessFunctions as $results)
		{
			if (empty($results[2]))
				continue;
		
			$jf = array_merge($jf, $results[2]);
		
		}
	
		$this->jf = $jf;
		return $jf;
	
	}
	
	
	
	function scanforUnusedFunctions()
	{
		
		$invalidFunctions = array();
		
		foreach ($this->fileList as $file)
		{
			if (filesize($file) > 1024)
				continue;
		
		
			$contents = file_get_contents($file);
			
			if (!stringContains($contents, '<?'))
				continue;

			foreach ($this->jf as $functionName)
			{
				$functionNameT = trim($functionName);
			
				if (!isset($invalidFunctions[$functionNameT]))
					$invalidFunctions[$functionNameT] = -1;

				$invalidFunctions[$functionNameT] += preg_match_all('/\b'.$functionNameT.'\b/', $contents, $matches);
			
			}
		}		
		
		asort($invalidFunctions);
		return $invalidFunctions;
	}
	
	
}


