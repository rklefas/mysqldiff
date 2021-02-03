<?php



// Trims strings that are in an array

function trimStringArray($arr)
{
	$new = null;

	if (count($arr) > 0)
		foreach ($arr as $item)
			$new[] = trim($item);
		
	return $new;
}



function flattenArray($arrayToFlatten)
{

	$result = Array();

	if (is_array($arrayToFlatten))
	{
		foreach ($arrayToFlatten as $inside)
		{
			if (is_array($inside))
				subFlatten($inside, $result);
			else
				$result[] = $inside;
		}
	}
	else
		return $arrayToFlatten;
		
	return $result;
}


function subFlatten($arrayToFlatten, &$result)
{

	foreach ($arrayToFlatten as $inside)
	{
		if (is_array($inside))
			subFlatten($inside, $result);
		else
			$result[] = $inside;
	}

	

}




function open_image ($file) { 
    # JPEG: 
    $im = @imagecreatefromjpeg($file); 
    if ($im !== false) { return $im; } 

    # GIF: 
    $im = @imagecreatefromgif($file); 
    if ($im !== false) { return $im; } 

    # PNG: 
    $im = @imagecreatefrompng($file); 
    if ($im !== false) { return $im; } 

    # GD File: 
    $im = @imagecreatefromgd($file); 
    if ($im !== false) { return $im; } 

    # GD2 File: 
    $im = @imagecreatefromgd2($file); 
    if ($im !== false) { return $im; } 

    # WBMP: 
    $im = @imagecreatefromwbmp($file); 
    if ($im !== false) { return $im; } 

    # XBM: 
    $im = @imagecreatefromxbm($file); 
    if ($im !== false) { return $im; } 

    # XPM: 
    $im = @imagecreatefromxpm($file); 
    if ($im !== false) { return $im; } 

    # Try and load from string: 
    $im = @imagecreatefromstring(file_get_contents($file)); 
    if ($im !== false) { return $im; } 

    return false; 
} 







function extractRange($array, $start, $end)
{
	$newArray = array();
	
	if (is_array($array))
		foreach ($array as $key => $val)
			if ($key >= $start)
				if ($key <= $end)
					$newArray[] = $val;


	return $newArray;
}








function colorfulPHPInfo()
{
	ob_start();
	phpinfo();
	$phpinfo = ob_get_contents();
	ob_end_clean();


	if (!isset($_GET['default']))
	{
		preg_match_all('/#[0-9a-fA-F]{6}/', $phpinfo, $rawmatches);
		for ($i = 0; $i < count($rawmatches[0]); $i++)
		   $matches[] = $rawmatches[0][$i];
		$matches = array_unique($matches);

		$hexvalue = '0123456789abcdef';

		$j = 0;
		foreach ($matches as $match)
		{
		   $r = '#';
		   $searches[$j] = $match;
		   for ($i = 0; $i < 6; $i++)
		      $r .= substr($hexvalue, mt_rand(0, 15), 1);
		   $replacements[$j++] = $r;
		   unset($r);
		}

		for ($i = 0; $i < count($searches); $i++)
		   $phpinfo = str_replace($searches, $replacements, $phpinfo);
	}
	echo $phpinfo;
}




// Prints contents of any variable in an easy-to-read scrollable box

function print_pre($v, $returnable = false, $backtraceIndex = 0)
{
	$back = debug_backtrace();
	
	static $calls;
	
	$calls = empty($calls) ? 1 : $calls + 1;
	
	//added $backtraceIndex so it could be called by a wrapper function 
	$meta = $back[$backtraceIndex]['file']." {".$back[$backtraceIndex]['line']."} -- Result #".$calls." ";

	if ( empty($v) || is_scalar($v) )
	{
		ob_start();
		var_dump($v);
		$print_r = ob_get_clean();
	
		$meta .= "using var_dump() ";
	
	}
	else
	{

		$print_r = print_r($v, true);
		$print_r = htmlentities($print_r);
		
		$meta .= "using print_r() ";
	}
	
	
	
	$vv = '
	<div style="
	padding: 2px;  border: black 1px solid; 
	width: 95%; max-height: 20em; text-align: left;  
	overflow: auto;  background-color: lightgray; 
	margin: 2px auto;
	">
		<div style="background-color: #aaa; ">'.$meta.'</div>
		<pre>' . $print_r . '</pre>
	</div>
	';
	
	
	if ($returnable)
		return $vv;
	
	echo $vv;
}


function phpMoreInfo()
{
	echo '<h1>Included Files</h1>';
	print_pre(get_included_files());

	echo '<h1>Memory Peak Usage</h1>';
	print_pre(memory_get_peak_usage());

	if (function_exists('getrusage'))
	{
		echo '<h1>getrusage</h1>';
		print_pre(getrusage());
	}
}
