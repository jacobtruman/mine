#!/usr/bin/php
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("Photo.class.php");

// Script to rename picture based on date taken value encoded into image
// TODO: need to add 12 hours to all NIKON D3200 images, then delete the source files to ensure no duplication
// TODO: Move NEW directory outside the year directory

$dry_run = false;

runProcess($dry_run);

function runProcess($dry_run = false) {
	$base_path = "/mine/Pictures/camera";
	$path = $base_path."/NEW";
	$files = glob($path."/*.{jpg,JPG}", GLOB_BRACE);
	foreach($files as $file) {
		$photo = new Photo($path, $file, $base_path);
		$photo->renameFile($dry_run);
	}
}

?>
