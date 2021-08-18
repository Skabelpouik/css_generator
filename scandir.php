<?php
function get_files($file, $options = FALSE)
{
	$tab_files = array();
	$dir = opendir($file);
	while (($dir_element = readdir($dir)) !== FALSE) 
	{
		if ($dir_element != "." && $dir_element != ".." 
			&& is_dir($file . "/" . $dir_element) && $options == TRUE) 
		{
			$tab_files = array_merge($tab_files, get_files($file . "/" . $dir_element, TRUE));
		}
		else if ($dir_element != "." && $dir_element != ".." 
			&& is_file($file . "/" . $dir_element))
		{
			$tab_files[] = $file . "/" . $dir_element;
		}
	}
	closedir($dir);
	return $tab_files;
}

function get_options($argv, $argc)
{
	$shortopts = "ri::s::p:";
	$longopts = array("recursive", "output-image::", "output-style::", "padding:");
	$opt = getopt($shortopts, $longopts);
	unset($argv[$argc - 1]);
	unset($argv[0]);
	array_walk($argv, function(&$key, $value) {
    	if(preg_match('/^-{2}.*/', $key)) {
    		$explode_key = explode("=", $key);
    		$key = $explode_key[0];
        	$key = str_replace('--', '', $key); 
    	} 
    	else if (preg_match('/^-{1}.*/', $key)) {
    		$split_key = str_split($key, 2);
    		$key = $split_key[0];
        	$key = str_replace('-', '', $key); 
    	}
	});
	$argv = array_flip($argv);
	$array_diff = array_diff_key($argv, $opt);
	$str_diff = implode(", ", array_keys($array_diff));
	if (!empty($array_diff)){
			throw new Exception("Erreur: options "  . $str_diff . " invalides.\n");
		}
	return $opt;
}
?>