#!/usr/bin/php
<?php
// TODO: prevent the year of a show from being tacked on to the directory name
require_once("TVShow.class.php");
require_once("Logger.class.php");

runProcess();

function runProcess()
{
	$file_dir = "/torrents/data/done";
	$new_file_dir = "/mine/TVShows";
	$logger = new Logger("/mine/logs/TVShows_".date("Y-m-d").".log");
	$files = glob(quotemeta($file_dir)."/*");

    if(count($files)) {
        foreach($files as $file) {
            list($show, $file_actual) = processFile($file);
            if(is_object($show) && get_class($show) == "TVShow") {
                $shows[$file_actual] = $show;
            }
        }
    }

    if(count($shows)) {
        foreach($shows as $file=>$show) {
            if($show->isValid()) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $file_path = $new_file_dir."/".$show->getShowFolder()."/Season ".$show->getSeasonNumber();
                if(!is_dir($file_path)) {
                    mkdir($file_path, 0777, true);
                }
                $file_name = $show->getShowString()." - ".$show->getEpisodeString().".".$ext;
                $new_file = $file_path."/".$file_name;
                $cmd = 'mv "'.$file.'" "'.$new_file.'"';
                $logger->addToLog($cmd);
                exec($cmd);
            } else {
                $logger->addToLog($show->getInvalidReason());
            }
        }
    }
}

function processFile($file) {
	if(!is_dir($file)) {
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if(!in_array($ext, array("avi", "mp4", "m4v", "!qb", "mkv"))) {
			return array(NULL, NULL);
		} else {
			$showstring = pathinfo($file, PATHINFO_FILENAME);
			$show = new TVShow($showstring);
			return array($show, $file);
		}
	} else {
        if(!stristr($file, "sample")) {
            $files = glob(quotemeta($file)."/*");
            foreach($files as $this_file) {
                $ret_val = processFile($this_file);
                if($ret_val[0] !== NULL) {
                    return $ret_val;
                }
            }
        }
    }
}

?>
