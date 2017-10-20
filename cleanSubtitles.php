#!/usr/bin/php
<?php

$search_strings = array("openSubtitles", "translated by", "www.bsubs.com", "please rate this subtitle");
$plex_dir = "/var/lib/plexmediaserver/Library/Application Support/Plex Media Server/Media/localhost";
$subtitles_subdir = "Contents/Subtitle Contributions/com.plexapp.agents.opensubtitles/en";

$dirs = glob($plex_dir."/*");

foreach($dirs as $dir) {
	if(is_dir($dir)) {
		$files_in_dir = glob($dir . "/*");
		foreach($files_in_dir as $file_in_dir) {
			if(is_dir($file_in_dir) && is_dir("{$file_in_dir}/{$subtitles_subdir}")) {
				$subtitle_files = glob("{$file_in_dir}/{$subtitles_subdir}/*.srt");
				$lines_to_delete = array();
				foreach($subtitle_files as $file) {
					$update = false;
					echo "Working on file: {$file}" . PHP_EOL;
					$cmd = "head " . addcslashes($file, " ");
					$contents = file_get_contents($file);
					foreach($search_strings as $search_string) {
						$pos = stripos($contents, $search_string);
						if($pos !== FALSE) {
							$update = true;
							$lines = explode("\r\n", $contents);
							$line_count = count($lines);
							foreach($lines as $i=>$line) {
								if(stripos($line, $search_string) !== FALSE) {
									$num = $i;
									while($num >= 0 && !empty(trim($lines[$num]))) {
										$lines_to_delete[] = $num;
										$num--;
									}
									$num = $i + 1;
									while($num <= $line_count && isset($lines[$num]) && !empty(trim($lines[$num]))) {
										$lines_to_delete[] = $num;
										$num++;
									}
								}
							}
							foreach($lines_to_delete as $num) {
								#echo "Deleting line {$num}" . PHP_EOL;
								unset($lines[$num]);
							}
						}
					}
					if($update) {
						$new_contents = implode("\r\n", $lines);
						file_put_contents($file, $new_contents);
					}
				}
			}
		}
	}
}
