#!/usr/bin/php
<?

require_once("TVShow.class.php");
require_once("Logger.class.php");

runProcess();

function runProcess()
{
	$file_dir = "/torrents/.torrents";
    $new_file_dir = "/mine/Videos";
	$logger = new Logger("/mine/logs/TVShows_".date("Y-m-d").".log");
	$files = glob($file_dir."/*.mp4");

	foreach($files as $file) {
		if(!is_dir($file)) {
			$file_parts = explode("/", $file);
			$filename = end($file_parts);
			$filename_parts = explode(".", $filename);
			$ext = end($filename_parts);
			unset($filename_parts[count($filename_parts) - 1]);
			$showstring = implode(".", $filename_parts);
			$shows[$file] = new TVShow($showstring);
		}
	}

	foreach($shows as $file=>$show) {
		if($show->isValid()) {
			$file_path = $new_file_dir."/".$show->show."/Season ".$show->season;
			if(!is_dir($file_path)) {
				mkdir($file_path, 0777, true);
			}
			$file_name = $show->show." - ".$show->episode.".".$ext;
			$new_file = $file_path."/".$file_name;
			$cmd = 'mv "'.$file.'" "'.$new_file.'"';
			exec($cmd);
			$logger->addToLog($cmd);
		}
	}
}

?>
