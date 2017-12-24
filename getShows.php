<?php

require_once("TVShowFetch.class.php");

$configs_dir = "./configs";
$configs = array(
	"cbs_shows.json" => "getCBSShows",
	"nbc_shows.json" => "getNBCShows",
	"cw_shows.json" => "getCWShows",
	"abc_shows.json" => "getABCShows",
#	"fox_shows.json" => "getFoxShows",
	"shows.txt" => "getShowsFromFile"
);

$tvsf = new TVShowFetch();

foreach($configs as $file=>$method) {
	$tvsf->processFile("{$configs_dir}/{$file}", $method);
}