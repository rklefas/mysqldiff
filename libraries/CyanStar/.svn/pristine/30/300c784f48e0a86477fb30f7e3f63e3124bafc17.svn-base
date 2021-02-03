<?php


class FileLogger
{
	static function append($file, $text, $maxlength = 1000)
	{
		return file_put_contents($file, substr($text, 0, $maxlength)."\n\n", FILE_APPEND);
	}
	


}