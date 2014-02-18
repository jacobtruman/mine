<?php

$ext = ".JPG";
$dir = "/chell/d/Pictures/camera/2013/NEW/";
$files = glob($dir."*".$ext);

foreach($files as $file) {
	// get the file number
	$num = str_replace(array("DSC_", $dir, $ext), "", $file);
	var_dump($num);
}

?>
