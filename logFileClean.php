#!/usr/bin/php
<?php

require_once("DirectoryCleaner.class.php");

//$log_dirs = array("/raid/scripts/logs", "/raid/logs");
$log_dirs = array("/raid/logs");

foreach($log_dirs as $dir) {
	if(file_exists($dir)) {
		echo "Working on directory: \"{$dir}\"".PHP_EOL;
		$c = new DirectoryCleaner($dir);
		$c->runProcess(".log");
	} else {
		echo "Directory does not exist: \"{$dir}\"".PHP_EOL;
	}
}

?>
