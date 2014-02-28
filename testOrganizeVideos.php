#!/usr/bin/php
<?
// TODO: prevent the year of a show from being tacked on to the directory name
require_once("TVShow.class.php");
require_once("Logger.class.php");

runProcess();

function runProcess()
{
	$file_dir = "/torrents/.torrents";
	$file_dir = "/torrents/downloading";
	$new_file_dir = "/mine/Videos";
	$logger = new Logger("/mine/logs/TVShows_".date("Y-m-d").".log");
	$files = glob(quotemeta($file_dir)."/*");

	foreach($files as $file) {
		list($show, $file_actual) = processFile($file);
		if(get_class($show) == "TVShow") {
			$shows[$file_actual] = $show;
		}
	}

	foreach($shows as $file=>$show) {
		if($show->isValid()) {
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			$file_path = $new_file_dir."/".$show->show."/Season ".$show->season;
			if(!is_dir($file_path)) {
				mkdir($file_path, 0777, true);
			}
			$file_name = $show->show." - ".$show->episode.".".$ext;
			$new_file = $file_path."/".$file_name;
			$cmd = 'mv "'.$file.'" "'.$new_file.'"';
			$logger->addToLog($cmd);
		} else {
			$logger->addToLog($show->getInvalidReason());
		}
	}
}

function processFile($file) {
	if(!is_dir($file)) {
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if(!in_array($ext, array("avi", "mp4", "!qb"))) {
			return array(NULL, NULL);
		} else {
			$showstring = pathinfo($file, PATHINFO_FILENAME);
			$show = new TVShow($showstring);
			return array($show, $file);
		}
	} else {
		$files = glob(quotemeta($file)."/*");
		foreach($files as $this_file) {
			$ret_val = processFile($this_file);
			if($ret_val[0] !== NULL) {
				return $ret_val;
			}
		}
	}
}

?>
