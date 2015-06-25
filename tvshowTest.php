#!/usr/bin/php
<?
// TODO: prevent the year of a show from being tacked on to the directory name
require_once("TVShow2.class.php");

runProcess();

function runProcess() {
	$showstrings = array(
		"greys.anatomy.1120.hdtv-lol.mp4",
		"castle.2009.719.hdtv-lol.mp4",
		"The.Flash.2014.S01E05.HDTV.x264-LOL",
		"Elementary.S03E05.HDTV.x264-LOL",
		"Lip.Sync.Battle.S01E04.Anna.Kendrick.vs.John.Krasinski.HDTV.x264-FiHTV",
		"Lip.Sync.Battle.S01E03.Anne.Hathaway.vs.Emily.Blunt.HDTV.x264-FiHTV"
	);
	foreach($showstrings as $showstring) {
		$show = new TVShow($showstring);

		if($show->isValid()) {
			$file_path = $show->show_folder."/Season ".$show->season_number;
			$file_name = $show->show." - ".$show->getEpisodeString();
			$new_file = $file_path."/".$file_name;
			echo "SHOW: ".$new_file.PHP_EOL;
			//exec($cmd);
		}
	}
}

?>
