<?

$yearmonth_pattern = '/[0-9]{4}\/(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i';
$path = "/mine/testPics/2013/Nov/";

preg_match($yearmonth_pattern, $path, $matches);
var_dump($matches);

?>
