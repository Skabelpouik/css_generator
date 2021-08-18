<?php
include "sprite.php";
include "scandir.php";

function css_generator($file, $argv, $argc, $output = "sprite.png", $output_style = "style.css", $padding = 0)
{
	if (!is_dir($file))
	{
		throw new Exception("Erreur: pas de dossier passé en argument.\n");
	}
	if (!is_writable($file))
	{
		throw new Exception("Erreur: vous n'avez pas les droits d'accès sur ce dossier.\n");
	}
	$options = get_options($argv, $argc);	
	if (isset($options["r"]) || isset($options["recursive"]))
	{
		$files = get_files($file, TRUE);
	}
	else
	{
		$files = get_files($file);
	}
	$output = get_outputimg($argv, $argc);
	$output_style = get_outputstyle($argv, $argc);
	$padding = get_padding($argv, $argc);
	sprite($files, $output, $output_style, $padding);
}

function get_outputimg($argv, $argc)
{
	$options = get_options($argv, $argc);	
	if (isset($options["i"]) && $options["i"] !== FALSE)
	{
		$output = $options["i"];
		return $output;
	}
	else if (isset($options["output-image"]) 
		&& $options["output-image"] !== FALSE)
	{
		$output = $options["output-image"];
		return $output;
	}
	else 
	{
		$output = "sprite.png";
		return $output;
	}
}

function get_outputstyle($argv, $argc)
{
	$options = get_options($argv, $argc);
	if (isset($options["s"]) && $options["s"] !== FALSE)
	{
		$output_style = $options["s"];
		return $output_style;
	}
	else if (isset($options["output-style"]) 
		&& $options["output-style"] !== FALSE)
	{
		$output_style = $options["output-style"];
		return $output_style;
	}
	else 
	{
		$output_style = "style.css";
		return $output_style;
	}
}

function get_padding($argv, $argc)
{
	$options = get_options($argv, $argc);
	if (isset($options["p"]))
	{
		$padding = $options["p"];
		return $padding;
	}
	else if (isset($options["padding"]))
	{
		$padding = $options["padding"];
		return $padding;
	}
	else 
	{
		$padding = 0;
		return $padding;
	}
}

try{
	css_generator($argv[$argc - 1], $argv, $argc);
}
catch(Exception $e){
	echo $e->getMessage();
}
?>