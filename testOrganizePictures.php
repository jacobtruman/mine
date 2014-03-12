#!/usr/bin/php
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("Photo.class.php");

// Script to rename picture based on date taken value encoded into image
// TODO: need to add 12 hours to all NIKON D3200 images, then delete the source files to ensure no duplication
// TODO: Move NEW directory outside the year directory

runProcess();

function runProcess() {
	$path = "/mine/backup/Pictures/camera/2013/Nov";
	$files = glob($path."/*.jpg");
	foreach($files as $file) {
		$photo = new Photo($path, $file);
		$photo->renameFile();
	}
}

?>
