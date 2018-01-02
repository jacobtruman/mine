<?php

require_once("TVShowFetch.class.php");

$params = processArgs();

$config_files = glob("{$params['configs_dir']}/*.json");

$tvsf = new TVShowFetch($params);

foreach($config_files as $config_file) {
	$config = json_decode(file_get_contents($config_file), true);
	$tvsf->processConfig($config);
}

function processArgs() {
	$args = getopt("elvc:f:");

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

	$filter = null;
	if(isset($args['f'])) {
		$filter = $args['f'];
	}

	return array("execute" => $execute, "latest" => $latest, "verbose" => $verbose, "configs_dir" => $configs_dir, "filter" => $filter);
}