<?php



class ImageResizer
{

	static function checkIfModified()
	{
	
		header("Cache-Control: private, max-age=10800, pre-check=10800");
		header("Pragma: private");
		header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
		// the browser will send a $_SERVER['HTTP_IF_MODIFIED_SINCE'] 
		// option 1, you can just check if the browser is sendin this
		
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		{
			// if the browser has a cached version of this image, send 304
			header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
			exit;
		}		
		
		return true;
	}




	static function display($imgString, $parameters, $storeInFile, $jpegQuality, $returnAsString = false, $forceRefresh = false)
	{

		if (!is_file($storeInFile) || $forceRefresh)
		{
			$image = imagecreatefromstring($imgString);
			
			if ($image === false)
			{
				trigger_error('Image format not supported.', E_USER_WARNING);
				return false;
			}
			
			$org_width = imagesx($image); 
			$org_height = imagesy($image);
			
			$dimensionArray = self::dimensionGenerator($org_width, $org_height, $parameters);
			
			$image_resized = imagecreatetruecolor($dimensionArray['desired_width'], $dimensionArray['desired_height']); 
			$white = imagecolorallocate($image_resized, 255, 255, 255);
			imagefill($image_resized, 0, 0, $white);

			imagecopyresampled($image_resized, $image, 
				$dimensionArray['dest_x'], 
				$dimensionArray['dest_y'], 
				$dimensionArray['src_x'], 
				$dimensionArray['src_y'], 
				$dimensionArray['dest_width'], 
				$dimensionArray['dest_height'], 
				$dimensionArray['src_width'], 
				$dimensionArray['src_height']
			); 
			imagejpeg($image_resized, $storeInFile, $jpegQuality);
		}

		if ($returnAsString)
			return file_get_contents ( $storeInFile );

		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($storeInFile)) . ' GMT');
		header("Content-Length: ".filesize($storeInFile) );
		Header("Content-type: image/jpeg");
		exit( file_get_contents ( $storeInFile ) );
	}
	
	
	
/* 	
	static private function heightGivenWidth($width, $aspectRatio)
	{
	
	
	}
	
	
	
	static private function widthGivenHeight($height, $aspectRatio)
	{
	
	
	}
 */	
	
	
	
	static private function dimensionGenerator($org_width, $org_height, $parameters, $floatingPointPrecision = 6)
	{
		$input_xstart = FastGetBuilder::get('xcropstart', $parameters);
		$input_ystart = FastGetBuilder::get('ycropstart', $parameters);
		
		$input_width = FastGetBuilder::get('width', $parameters);
		$input_height = FastGetBuilder::get('height', $parameters);
		$input_maxwidth = FastGetBuilder::get('maxwidth', $parameters);
		$input_maxheight = FastGetBuilder::get('maxheight', $parameters);
		
		// May be:  stretch, crop, fitall, resize
		$input_overflowMethod = FastGetBuilder::get('overflowMethod', $parameters);
		
		$width = ($input_width) ? $input_width : $org_width;
		$height = ($input_height) ? $input_height : $org_height;
		
		
		if ($input_maxheight)
			if ($height > $input_maxheight)
			{
				$height = $input_maxheight;
				$input_height = $input_maxheight;
			}
			
			
		
		if ($input_maxwidth)
			if ($width > $input_maxwidth)
			{
				$width = $input_maxwidth;
				$input_width = $input_maxwidth;
			}


			
		// May remove the edges of the image
			
		if ($input_overflowMethod == 'crop')
		{
			$return['dest_x'] = 0;
			$return['dest_y'] = 0;
			$return['dest_width'] = $width;
			$return['dest_height'] = $height;

			$return['desired_width'] = $width;
			$return['desired_height'] = $height;

			if ($input_xstart !== null)
				$src_width_calc = $input_xstart;
			else
				$src_width_calc = ($org_width - $width) / 2;

			if ($input_ystart !== null)
				$src_height_calc = $input_ystart;
			else
				$src_height_calc = ($org_height - $height) / 2;

			
			$return['src_x'] = $src_width_calc;
			$return['src_y'] = $src_height_calc;
			$return['src_width'] = $width;
			$return['src_height'] = $height;
		
		}
		
		// May place borders around the image
		
		else if ($input_overflowMethod == 'fitall')
		{

			$hRatio = $height / $org_height;
			$wRatio = $width / $org_width;
		
			if ($wRatio < $hRatio)
			{
				$dest_x = 0;
				$dest_height = $wRatio * $org_height;
				$dest_width = $width;
				$dest_y = ($height - $dest_height) / 2;
			}
			else if ($wRatio > $hRatio)
			{
				$dest_y = 0;
				$dest_height = $height;
				$dest_width = $hRatio * $org_width;
				$dest_x = ($width - $dest_width) / 2;
			}
			else
			{
				$dest_x = 0;
				$dest_y = 0;
				$dest_height = $height;
				$dest_width = $width;
			}
			

			$return['dest_x'] = $dest_x;
			$return['dest_y'] = $dest_y;
			$return['dest_width'] = $dest_width;
			$return['dest_height'] = $dest_height;

			$return['desired_width'] = $width;
			$return['desired_height'] = $height;

			$return['src_x'] = 0;
			$return['src_y'] = 0;
			$return['src_width'] = $org_width;
			$return['src_height'] = $org_height;
			
			
//			print_pre($return); exit; 
		
		}
		
		else if ($input_overflowMethod == 'resize-crop')
		{

			$hRatio = $height / $org_height;
			$wRatio = $width / $org_width;
		
			if ($wRatio > $hRatio)
			{
				$dest_height = $wRatio * $org_height;
				$dest_width = $width;
				$dest_x = 0;
				$dest_y = ($height - $dest_height) / 2;
			}
			else if ($wRatio < $hRatio)
			{
				$dest_height = $height;
				$dest_width = $hRatio * $org_width;
				$dest_x = ($width - $dest_width) / 2;
				$dest_y = 0;
			}
			else
			{
				$dest_height = $wRatio * $org_height;
				$dest_width = $hRatio * $org_width;
				$dest_x = ($width - $dest_width) / 2;
				$dest_y = ($height - $dest_height) / 2;
			}
			

			$return['dest_x'] = $dest_x;
			$return['dest_y'] = $dest_y;
			$return['dest_width'] = $dest_width;
			$return['dest_height'] = $dest_height;

			$return['desired_width'] = $width;
			$return['desired_height'] = $height;

			$return['src_x'] = 0;
			$return['src_y'] = 0;
			$return['src_width'] = $org_width;
			$return['src_height'] = $org_height;
		
		}


		// May distort the image
		
		else if ($input_overflowMethod == 'stretch' || empty($input_overflowMethod))
		{
			$return['dest_x'] = 0;
			$return['dest_y'] = 0;
			
			$return['dest_height'] = $height;
			$return['desired_height'] = $height;
			$return['dest_width'] = $width;
			$return['desired_width'] = $width;
			
			$return['src_x'] = 0;
			$return['src_y'] = 0;
			$return['src_width'] = $org_width;
			$return['src_height'] = $org_height;
		}
		
		// Will always maintain aspect ratio
		
		else if ($input_overflowMethod == 'resize')
		{
		
			$return['dest_x'] = 0;
			$return['dest_y'] = 0;
			
			$widthDifferencial = ($width / $org_width);
			$heightDifferencial = ($height / $org_height);
			
			$originalAspectRatioWH = (string) (round($org_width / $org_height, $floatingPointPrecision));
			$originalAspectRatioHW = (string) (round($org_height / $org_width, $floatingPointPrecision));		
			
			$adjustedHeight = ($input_height) ? $height : $org_height * $widthDifferencial;
			$adjustedWidth = ($input_width) ? $width : $org_width * $heightDifferencial;
			
			$newAspectRatioWH = (string) (round($adjustedWidth / $adjustedHeight, $floatingPointPrecision));
			$newAspectRatioHW = (string) (round($adjustedHeight / $adjustedWidth, $floatingPointPrecision));

			// echo $widthDifferencial . " widthDifferencial<br />";
			// echo $heightDifferencial . " heightDifferencial<br />";
			// echo $adjustedHeight . " adjustedHeight<br />";
			// echo $adjustedWidth . " adjustedWidth<br />";
			// echo $originalAspectRatioWH . " originalAspectRatioWH<br />";
			// echo $originalAspectRatioHW . " originalAspectRatioHW<br />";
			// echo $newAspectRatioWH . " newAspectRatioWH<br />";
			// echo $newAspectRatioHW . " newAspectRatioHW<br />";
			
			
			if ( $originalAspectRatioWH == $newAspectRatioWH)
			{
			//	echo 'condtion 1';
			
				$return['desired_height'] = $adjustedHeight;
				$return['dest_height'] = $adjustedHeight;
				
				$return['dest_width'] = $adjustedWidth;
				$return['desired_width'] = $adjustedWidth;
			}
			
			else if ($originalAspectRatioWH > $newAspectRatioWH)
			{
			//	echo 'condtion 2';
			
				$return['desired_height'] = $width * $originalAspectRatioHW;
				$return['dest_height'] = $width * $originalAspectRatioHW;
				
				$return['dest_width'] = $width;
				$return['desired_width'] = $width;
			}
			else if ($originalAspectRatioWH < $newAspectRatioWH)
			{
			//	echo 'condtion 3';
			
				$return['desired_width'] = $height * $originalAspectRatioWH;
				$return['dest_width'] = $height * $originalAspectRatioWH;
				
				$return['dest_height'] = $height;
				$return['desired_height'] = $height;
			}
			
			
			$return['src_x'] = 0;
			$return['src_y'] = 0;
			$return['src_width'] = $org_width;
			$return['src_height'] = $org_height;
			
//			print_pre($return); exit;

		}
		else
			trigger_error('Invalid overflow method supplied: '.$input_overflowMethod, E_USER_WARNING);
		

		
		return $return;
	}
	




}