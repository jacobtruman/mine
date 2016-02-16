#!/usr/bin/php
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("Photo.class.php");

runProcess();

function runProcess() {
	$args = getopt("s:d:evtr");

	$dry_run = true;
	if(isset($args['e'])) {
		$dry_run = false;
	}

	$verbose = false;
	if(isset($args['v'])) {
		$verbose = true;
	}

	$trash = false;
	if(isset($args['t'])) {
		$trash = true;
	}

	$recurs = false;
	if(isset($args['r'])) {
		$recurs = true;
	}

	$source_path = "/tmp/PicturesNEW";
	if(isset($args['s'])) {
		$source_path = $args['s'];
	}
	$dest_path = "/tmp/Pictures";
	if(isset($args['d'])) {
		$dest_path = $args['d'];
	}

	// TODO: add recursive support

	$files = glob($source_path."/*.{jpg,JPG}", GLOB_BRACE);
	foreach($files as $file) {
		$photo = new Photo($file, $dest_path, $dry_run, $verbose, $trash);
		$photo->setTable("images2");
		$photo->renameFile();
	}
}

?>
