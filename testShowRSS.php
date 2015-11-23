#!/usr/bin/php
<?php

require_once("TVShow.class.php");
require_once("Logger.class.php");

addLog("#### Starting process ####");
runProcess();
addLog("#### Ending process ####");

function runProcess() {
	$rss = "http://showrss.info/rss.php?user_id=68354&hd=null&proper=null&magnets=true";

	$contents = file_get_contents($rss);

	if(!empty($contents)) {
		$xml = new SimpleXMLElement($contents);

		$json = json_encode($xml);
		$item_array = json_decode($json, true);

		$items = isset($item_array['channel']['item']) ? $item_array['channel']['item'] : array();

		processItems($items);
	}
}

function processItems($items) {
	addLog(count($items)." to be checked");
	if(count($items) > 0) {
		foreach($items as $index=>$item) {
			$show = new TVShow($item['title']);
			$url = $item['link'];

			addLog($show->show.": ".$show->getEpisodeString());
			$filename = str_replace(" ", "_", $show->show." - ".$show->getEpisodeString().".torrent");

			getTorrent($url, $filename);
		}
	}
}

function getTorrent($url, $filename) {
	preg_match('#magnet:\?xt=urn:btih:(?<hash>.*?)&dn=(?<filename>.*?)&tr=(?<trackers>.*?)$#', $url, $magnet_link);
	$hash = strtolower($magnet_link['hash']);
	$torrenttmp_dir = "/torrents/.torrentstmp";
	$torrent_dir = "/torrents/.torrents";
	chdir($torrenttmp_dir);

	$full_filename = "{$torrenttmp_dir}/{$hash}.torrent";
	$full_filename_invalid = "{$torrent_dir}/{$hash}.invalid";
	$final_filename = "{$torrent_dir}/{$filename}";
	if(file_exists($full_filename_invalid)) {
		echo "Invalid file found - deleting it: ".$full_filename_invalid."\n";
		unlink($full_filename);
		unlink($full_filename_invalid);
	}
	if(!file_exists($full_filename) && !file_exists($final_filename)) {
		addLog("\tGetting file: ".$full_filename." from ".$url);
		$cmd = "aria2c --bt-metadata-only=true --bt-save-metadata=true '{$url}' -d {$torrenttmp_dir}";
		exec($cmd, $output);
		copy($full_filename, $final_filename);
	} else {
		if(file_exists($full_filename)) {
			addLog("\ttmp file exists: {$full_filename}");
		}
		if(file_exists($final_filename)) {
			addLog("\tfinal file exists: {$final_filename}");
		}
		//getTorrentInfo($full_filename);
	}
	chdir(dirname(__FILE__));
}

function getTorrentInfo($torrent_file = "") {
	if(!empty($torrent_file) && file_exists($torrent_file)) {
		var_dump(`btshowmetainfo $torrent_file`);
	}
}

function addLog($msg) {
	echo date("Y-m-d h:i:s")."\t".$msg."\n";
}

?>
