<?php


require  dirname(__FILE__)."/std_library.php";


spl_autoload_register('CyanStarLibraryAutoloader');



function CyanStarLibraryAutoloader($class)
{
	$classfiles = glob(dirname(__FILE__)."/*/{$class}.php");
		
	foreach ($classfiles as $classfile)
	{		
		require $classfile;
		return true;
	}

	return false;
}



function isDevelopmentEnvironment()
{
	return strpos($_SERVER['SERVER_NAME'], "localhost") !== false;
}



