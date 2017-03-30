#!/usr/bin/php
<?php

$exts = array(".mov", ".mp4", ".m4v", ".3gp", ".mts", ".mpg", ".avi");
$files = glob("./*.".getPattern($exts));

foreach($files as $file) {
	if(strstr($file, " ")) {
		$new_file = str_replace(" ", "_", $file);
		if(!file_exists($new_file)) {
			if(rename($file, $new_file)) {
				$file = $new_file;
			} else {
				throw new Exception("Unable to rename {$file} to {$new_file}");
			}
		} else {
			throw new Excpetion("File already exists {$new_file}");
		}
	}
	$out_file = str_ireplace($exts, ".mkv", $file);
	if(!file_exists($out_file)) {
		$cmd = "convertvid {$file} {$out_file}";
		echo $cmd.PHP_EOL;
		passthru($cmd);
	} else {
		echo "File already exists: {$out_file}" . PHP_EOL;
	}
	if(file_exists($out_file)) {
		rename($file, $file.".converted");
	}
}

function getPattern($exts) {
	foreach($exts as $ext) {
		for($i = 0; $i < strlen($ext); $i++) {
			$char = $ext[$i];
			if($char === ".") {
				continue;
			}
			if(!isset($chars[$i])) {
				$chars[$i] = "";
			}
			$chars[$i] .= strtolower($char).strtoupper($char);
		}
	}

	return "[".implode("][", $chars)."]";
}

?>
