<?php


class Pagination
{

	static function range($page, $pageSize, $totalResults)
	{		
		$end = $page * $pageSize;
		$start = $end - $pageSize + 1;

		if ($end > $totalResults)
			$end = $totalResults;
			
		if ($start > $totalResults)
			$start = $totalResults;

		return array("start" => $start, "end" => $end);
	}





}