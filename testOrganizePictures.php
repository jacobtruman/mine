#!/usr/bin/php
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("Photo.class.php");

runProcess();

function runProcess() {
	$args = getopt("s:d:ev");

	$dry_run = true;
	if(isset($args['e'])) {
		$dry_run = false;
	}

	$verbose = false;
	if(isset($args['v'])) {
		$verbose = true;
	}

	$base_path = "/mine/Pictures/camera";
	$dest_path = "/mine/Pictures2/camera";
	$path = $base_path."/NEW";
	$files = glob($path."/*.{jpg,JPG}", GLOB_BRACE);
	foreach($files as $file) {
		$photo = new Photo($path, $file, $base_path, $dry_run, $verbose);
		$photo->setDestPath($dest_path);
		$photo->renameFile();
	}
}

?>
