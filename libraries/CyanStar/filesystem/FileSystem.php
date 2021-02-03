<?php

class FileSystem
{

	static function import($v)
	{
		if (is_file($v))
		{
			require $v;
			return true;
		}
	
		return false;
	}

	static function fileName($filepath)
	{
		$breaks = explode(".", $filepath);
		
		if (count($breaks) > 1)
			array_pop($breaks);
		
		return implode('.', $breaks);
	}

	static function fileExtension($filepath)
	{
		$breaks = explode(".", $filepath);
		
		if (count($breaks) > 1)
			return array_pop($breaks);
		
		return null;
	}


	// Lists the first level of subdirectories and files of a given directory

	static function dirList($directory, $find = null) 
	{

		// create an array to hold directory list
		$results = array();

		// create a handler for the directory
		
		if (is_dir($directory))
		{
			$handler = opendir($directory);

			// keep going until all files in directory have been read
			while ($file = readdir($handler)) {

				// if $file isn't this directory or its parent, 
				// add it to the results array
				if ($find == null || LibStrings::stringContains($file, $find))
					if ($file != '.' && $file != '..')
						$results[] = $file;
			}

			// tidy up: close the handler
			closedir($handler);
		}
		// done!
		return $results;

	}


	// Lists all subdirectories (recursively) of a given directory

	static function subdirectoryList($root)
	{

		$dirs = array();


		if (is_dir($root))
		{
			$list = scandir($root);
			
			unset($list[0], $list[1]);

		
			foreach ($list as $directoryItem)
			{
				$path = $root . "/" . $directoryItem;

				if (is_dir($path))
				{
					$dirs[] = $path;
					$returns = self::subdirectoryList($path);
					
					if (count($returns) > 0)
						foreach ($returns as $newItem)
							$dirs[] = $newItem;
					
				}
			}
		}

		return $dirs;


	}


	// Recursively lists all files of a given directory

	static function fileList($root, $find = null)
	{
		$dirs = self::subdirectoryList($root);
		$files = array();
		
		if (count($dirs) > 0)
			foreach ($dirs as $dir)
			{
				$fileList = self::dirList($dir, $find);
				
				if (count($fileList) > 0)
					foreach ($fileList as $file)
						if (is_file($dir . "/" . $file))
							$files[] = $dir . "/" . $file;
			}

		$v = self::dirList($root, $find);
		
		if (count($v) > 0)
			foreach ($v as $last)
				if (is_file($root . "/" . $last))
					$files[] = $root . "/" . $last;
				
		return $files;
	}



	//+----------------------------------------------

	function smartUnlink($filepath)
	{
		if (file_exists($filepath))
			unlink($filepath);
	}

	//+----------------------------------------------

	function empty_directory($dirname, $maxAge = null, $check= "modify", $level = 1)  
	{
		// $maxAge is the number of seconds that a file is permitted to live
		// $check determines whether or not the check a file's last "access" time or it's last "modify" time


		if (is_dir($dirname))
			$dir_handle = opendir($dirname);
		else
			return false;
			
		$deleted = 0;
			
		while($file = readdir($dir_handle))  
		{
			if ($file != "."  &&  $file != "..")  
			{
				$filepath = $dirname."/".$file;
			
				if ($check == "modify")
					$fileAge = time() - filemtime($filepath);
				elseif ($check == "access")
					$fileAge = time() - fileatime($filepath);
				else
					$fileAge = 0;
			
				if (!is_dir($filepath) && ($fileAge > $maxAge || $maxAge == null))
				{
					unlink($filepath);	
					$deleted++;
				}
				else
				{
					$deleted += self::empty_directory($filepath, $maxAge, $check, $level + 1);
				}
			}
		}
		
		closedir($dir_handle);
		
	//	if ($level > 1)
	//		rmdir($dirname);
			
		return $deleted;
	} 



	function delete_directory($dirname)  
	{
		if (is_dir($dirname))
			$dir_handle = opendir($dirname);
			
		if (!$dir_handle)
			return false;
			
		while($file = readdir($dir_handle))  
		{
			if ($file  !=  "."  &&  $file  !=  "..")  
			{
				if  (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
				else
					self::delete_directory($dirname.'/'.$file);
			}
		}
		
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	} 



}

