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


class FileReferencer
{
	public $fileList;


	public function __construct()
	{
		$this->fileList = array();
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

	/* Check for file references */
	
	public function checkAgainstThisDirectory($dir)
	{
		$searchList = fileList($dir);

		foreach ($searchList as $searchingFile)
		{
			foreach ($this->fileList as $file)
			{
				$this->fileFound($file, $this->fileIsReferenced($searchingFile, $file));
			}
		}
	}

	private function fileFound($countThisFile, $addup = false)
	{
		if (!isset($this->files[$countThisFile]))
			$this->files[$countThisFile] = 0;
	
		if ($addup)
			$this->files[$countThisFile]++;
	}

	public function fileIsReferenced($checkingInThisFile, $forThisFile)
	{
		static $filename;
		static $contents;
		
		if ($filename != $checkingInThisFile)
		{
			if (is_file($checkingInThisFile))
				$contents = file_get_contents($checkingInThisFile);
			else
				throw new Exception($checkingForThisFile . ' is not a file!');
		}	

		$filename = $checkingInThisFile;
		return stringContains($contents, basename($forThisFile));
	}

	/* Show files that exist, but that are not called from any other files */
	
	public function showUnreferencedFiles($delete = false)
	{
		if (empty($this->files))
			return false;
			
		$unref = array();
			
		foreach ($this->files as $filename => $count)
		{
			if ($count > 0 || !is_file($filename))
				continue;
		
			$unref[] = $filename;

			if ($delete)
				unlink($filename);
		}
	
		print_pre($unref);
	
		return count($unref);	
	}	
}


