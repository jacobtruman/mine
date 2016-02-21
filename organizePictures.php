#!/usr/bin/php
<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("Photo.class.php");

runProcess();

function runProcess() {
	$args = getopt("s:d:evtg");

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

	$getdatetime = false;
	if(isset($args['g'])) {
		$getdatetime = true;
	}

	$source_path = "/mine/Pictures/NEW";
	if(isset($args['s'])) {
		$source_path = $args['s'];
	}
	$dest_path = "/mine/Pictures";
	if(isset($args['d'])) {
		$dest_path = $args['d'];
	}
	$files = glob($source_path."/*.{jpg,JPG}", GLOB_BRACE);
	$count = count($files);
	foreach($files as $i=>$file) {
		try {
			$photo = new Photo($file, $dest_path, $dry_run, $verbose, $trash);
			$photo->addProgressToLog($count, ($i + 1));
			if($getdatetime) {
				$date = $photo->getDateTimeFromFilename();
			} else {
				$photo->renameFile();
			}
		} catch (Exception $e) {
			echo $e->getMessage().PHP_EOL;
		}
	}
}

?>
