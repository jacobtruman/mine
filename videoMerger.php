#!/usr/bin/php
<?php

$main_dir = "/transcodertemp";

$folders = glob($main_dir."/*");

foreach($folders as $folder)
{
	if(is_dir($folder) && !strstr($folder, "plex"))
	{
		$file_parts = explode("/", $folder);
		$name = end($file_parts);
		$files = glob($folder."/media-*");

		sort($files);

		foreach($files as $file)
		{
			$cmd = "cat ".$file." >> ./".$name.".mp4";
			echo $cmd."\n";
			exec($cmd);
		}
	}
}

$files = glob("./*.mp4");

foreach($files as $file)
{
	// re-encode it to fix issues cause by catting
	$tmp_file = str_replace(".mp4", "_tmp.mp4", $file);
	$cmd = "mv ".$file." ".$tmp_file;
	exec($cmd);
	$cmd = "HandBrakeCLI -i ".$tmp_file." -o ".$file;
	echo $cmd."\n";
	exec($cmd);
}

?>
