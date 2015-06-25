#!/usr/bin/php
<?

require_once("TVShow.class.php");
require_once("Logger.class.php");

addLog("#### Starting process ####");
runProcess();
addLog("#### Ending process ####");

function runProcess() {
	$rss = "http://showrss.info/rss.php?user_id=68354&hd=null&proper=null&magnets=false";

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
	$torrenttmp_dir = "/torrents/.torrentstmp";
	$torrent_dir = "/torrents/.torrents";
	chdir($torrenttmp_dir);

	$full_filename = $torrenttmp_dir."/".$filename;
	$full_filename_invalid = $torrent_dir."/".$filename.".invalid";
	if(file_exists($full_filename_invalid)) {
		echo "Invalid file found - deleting it: ".$full_filename_invalid."\n";
		unlink($full_filename);
		unlink($full_filename_invalid);
	}
	if(!file_exists($full_filename)) {
		addLog("\tGetting file: ".$full_filename." from ".$url);
		$fp = fopen($full_filename, 'w+');//This is the file where we save the information
		$ch = curl_init($url);//Here is the file we are downloading
		curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // Important 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER,0); // None header
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); // Binary trasfer 1
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		copy($full_filename, $torrent_dir."/".$filename);
	} else {
		addLog("\tfile exists: ".$full_filename);
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
