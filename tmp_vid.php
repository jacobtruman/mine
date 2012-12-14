<?php

require_once("TVShowDev.class.php");

$files = array(
	"/torrents/.torrents/The.Big.Bang.Theory.S10E03.HDTV.x264-LOL[rarbg]/the.big.bang.theory.1003.hdtv-lol.mkv",
	"/torrents/.torrents/The.Good.Place.S01E04.HDTV.x264-FLEET[rarbg]/The.Good.Place.S01E04.HDTV.x264-FLEET.mkv"
);

foreach($files as $file) {
	$showstring = pathinfo($file, PATHINFO_FILENAME);
	$show = new TVShowDev($showstring);
	
	var_dump($show->getEpisodeString());
}

?>
