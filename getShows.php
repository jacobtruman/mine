#!/usr/bin/php
<?php

require_once("TVShowFetch.class.php");

$g = new GetShows();

class GetShows {

	public function __construct() {
		$params = $this->_parseArgs();

		$config_files = glob("{$params['configs_dir']}/*.json");
		$main_config_file = "{$params['configs_dir']}/config.json";
		if (file_exists($main_config_file)) {
			$config = json_decode(file_get_contents($main_config_file), true);
			$params = array_merge($params, $config);
		}

		$tvsf = new TVShowFetch($params);

		$lock = "/tmp/" . basename(__FILE__) . ".lock";
		@$f = fopen($lock, 'x');
		if ($f === false) {
			$tvsf->logger->addToLog("Can't acquire lock");
		} else {
			$tvsf->logger->addToLog("Lock acquired");

			foreach ($config_files as $config_file) {
				if ($config_file == $main_config_file) {
					continue;
				}
				$config = json_decode(file_get_contents($config_file), true);
				if($config !== null) {
					$tvsf->processConfig($config);
				} else {
					$tvsf->addToErrors("Config file '{$config_file}' is not valid JSON");
				}
			}
			// Cleanup the lock
			fclose($f);
			unlink($lock);
		}
	}

	protected function _parseArgs() {
		$args = getopt("healvkc:f:n:");

		if (isset($args['h'])) {
			$this->_usage();
		}

		$execute = false;
		if (isset($args['e'])) {
			$execute = true;
		}

		$all = null;
		$latest = null;
		if (isset($args['l']) && isset($args['a'])) {
			$this->_usage("The latest (l) and all (a) flags cannot both be specified");
		} else if (isset($args['l'])) {
			$latest = true;
		} else if (isset($args['a'])) {
			$all = true;
		}

		$verbose = false;
		if (isset($args['v'])) {
			$verbose = true;
		}

		$keep_files = false;
		if (isset($args['k'])) {
			$keep_files = true;
		}

		$configs_dir = dirname(__FILE__) . "/configs";
		if (isset($args['c'])) {
			$configs_dir = $args['c'];
		}

		$filter = null;
		if (isset($args['f'])) {
			$filter = $args['f'];
		}

		$networks = null;
		if (isset($args['n'])) {
			$networks = $args['n'];
		}

		$args = array(
			"execute" => $execute,
			"all" => $all,
			"latest" => $latest,
			"verbose" => $verbose,
			"keep_files" => $keep_files,
			"configs_dir" => $configs_dir,
			"filter" => $filter,
			"networks" => $networks
		);
		return $args;
	}

	private function _usage($msg = '') {
		if ($msg != '') {
			echo PHP_EOL . "$msg" . PHP_EOL;
		}

		$cmd = $_SERVER['argv'][0];
		die("Get Shows

      Usage: $cmd <options>
	  	-a                       Get all episodes - cannot be used with the latest (l) flag
	  	-l                       Get latest episode (default defined in network config file) - cannot be used with the all (a) flag
	  	-k                       Keep data files (defaults to false)
	  	-c                       Config files diirectory (defults to " . dirname(__FILE__) . "/configs)
	  	-f <filter string>       Filter to be applied to shows
	  	-n <network string>      Network for which to get shows
		-h                       Show this help
		-e                       Execute script
		-v                       Verbose logging
      " . PHP_EOL);
	}
}
