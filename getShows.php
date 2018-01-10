#!/usr/bin/php
<?php

require_once("TVShowFetch.class.php");

$params = processArgs();

$config_files = glob("{$params['configs_dir']}/*.json");

$tvsf = new TVShowFetch($params);

$lock = "/tmp/" . basename(__FILE__) . ".lock";
@$f = fopen($lock, 'x');
if ($f === false) {
	$tvsf->logger->addToLog("Can't acquire lock");
} else {
	$tvsf->logger->addToLog("Lock acquired");

	foreach($config_files as $config_file) {
		$config = json_decode(file_get_contents($config_file), true);
		$tvsf->processConfig($config);
	}
	// Cleanup the lock
	fclose($f);
	unlink($lock);
}

function processArgs() {
	$args = getopt("elvkc:f:n:");

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

	$keep_files = false;
	if(isset($args['k'])) {
		$keep_files = true;
	}

	$configs_dir = dirname(__FILE__) . "/configs";
	if(isset($args['c'])) {
		$configs_dir = $args['c'];
	}

	$filter = null;
	if(isset($args['f'])) {
		$filter = $args['f'];
	}

	$networks = null;
	if(isset($args['n'])) {
		$networks = $args['n'];
	}

	$args = array(
		"execute" => $execute,
		"latest" => $latest,
		"verbose" => $verbose,
		"keep_files" => $keep_files,
		"configs_dir" => $configs_dir,
		"filter" => $filter,
		"networks" => $networks
	);
	return $args;
}