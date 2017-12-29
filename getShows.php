<?php

require_once("TVShowFetch.class.php");

$configs = array(
	"cbs_shows.json" => "getCBSShows",
	"nbc_shows.json" => "getNBCShows",
	"cw_shows.json" => "getCWShows",
	"abc_shows.json" => "getABCShows",
	"fox_shows.json" => "getFoxShows",
	"shows.txt" => "getShowsFromFile"
);

$params = processArgs();

$tvsf = new TVShowFetch($params);

foreach($configs as $file=>$method) {
	$tvsf->processFile("{$params['configs_dir']}/{$file}", $method);
}

function processArgs() {
	$args = getopt("elvc:");

	$execute = false;
	if(isset($args['e'])) {
		$execute = true;
	}

	$latest = false;
	if(isset($args['l'])) {
		$latest = true;
	}

	$verbose = false;
	if(isset($args['v'])) {
		$verbose = true;
	}

	$configs_dir = "./configs";
	if(isset($args['c'])) {
		$configs_dir = $args['c'];
	}

	// this is the api key for Fox - not sure when/if it expires...
	$apiKey = "abdcbed02c124d393b39e818a4312055";

	return array("execute" => $execute, "latest" => $latest, "verbose" => $verbose, "configs_dir" => $configs_dir, "apiKey" => $apiKey);
}