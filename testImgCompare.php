#!/usr/bin/php
<?
#ini_set('display_errors', 1);
#error_reporting(E_ALL);

require_once("ImgCompare.class.php");

runProcess();

function runProcess() {
	$args = getopt("dbcfv");
	$verbose = false;
	if(isset($args['v'])) {
		$verbose = true;
	}

	$dir = "/mine/Pictures";
	$extensions = array("jpg", "png");
	//$filters = array("path"=>array("NEW"), "path_exclude"=>array("KatyPhone"));
	//$filters = array("path_exclude"=>array("thumbnails"));
	//$filters = array("path"=>array("2013"), "path_exclude"=>array("thumbnails", "Takeout"));
	$filters = array("path_exclude"=>array("thumbnails"));
	$imgCompare = new ImgCompare($dir, $extensions, $filters, $verbose);
	if(isset($args['b'])) {
		$imgCompare->buildDB();
	}
	if(isset($args['f'])) {
		$imgCompare->findDuplicates();
	}
	if(isset($args['c'])) {
		$imgCompare->cleanDB();
	}
}

?>
