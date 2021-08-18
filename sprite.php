<?php
function get_height($files){
	$imgheightmax = 0;
	foreach ($files as $file) 
	{
		$fileinfo = getimagesize($file);
		if ($fileinfo[1] > $imgheightmax)
		{
			$imgheightmax = $fileinfo[1];
		}
	}
	return $imgheightmax;
}

function get_width($files, $padding){
	$imgwidthsum = 0;
	foreach ($files as $file) 
	{
		$fileinfo = getimagesize($file);
		$imgwidthsum += ($padding + $fileinfo[1]);
	}
	return $imgwidthsum;
}

function sprite($files, $output, $output_style, $padding)
{
	$imgheightmax = get_height($files);
	$imgwidthsum = get_width($files, $padding);
	$i = 1;
	$pos = 0;
	$img = imagecreatetruecolor($imgwidthsum, $imgheightmax);
	foreach ($files as $file) {
		if (mime_content_type($file) != "image/png"){
			throw new Exception("Erreur: " . $file . " n'est pas au format png\n");
		}
		$tmp = imagecreatefrompng($file);
		$width = imagesx($tmp);
		$height = imagesy($tmp);
		imagecopy($img, $tmp, $pos, 0, 0, 0, $width, $height);
		generate_css($output_style, $output, $pos, $width, $height, $i);
		$pos += ($padding + $width);
		$i++;
		imagedestroy($tmp);
	}
	if (empty($output)){
		header("Content-Type: image/png");
		imagepng($img);
	}
	else{
		imagepng($img, $output);
	}
}

function generate_css($output_style, $output_image, $pos, $output_w, $output_h, $i){
		$css_string = "
			.img" . $i . "{
				display: inline-block;
				background: url('" . $output_image . "') no-repeat;
				overflow: hidden;
				background-position: -" . $pos . "px -0px;
				width: " . $output_w . "px;
				height: " . $output_h . "px;
			}";
		if (strpos($output_style, ".css") == FALSE)
		{
			$output_style = $output_style . ".css";
		}
		$css_file = fopen($output_style, "a");
		if (!headers_sent())
		{
			header("Content-Type: text/css");
			fwrite($css_file, $css_string);
		}
		else
		{
			fwrite($css_file, $css_string);
		}
		fclose($css_file);
}
?>