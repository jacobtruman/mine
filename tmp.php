<?php

/*$yearmonth_pattern = '/[0-9]{4}\/(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i';
$path = "/mine/testPics/2013/Nov/";

preg_match($yearmonth_pattern, $path, $matches);
var_dump($matches);*/

require_once("ImgCompare.class.php");

$file = "/mine/Pictures/camera/Masters/2015/05/23/20150523-213132/IMG_0012.JPG";

ImgCompare::processFile($file);

?>
