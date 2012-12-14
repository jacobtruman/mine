<?php

$host = "wheatley";
$base_dir = "/mine/Movies";
$files = glob("*.mkv");

foreach($files as $file) {
	$start_pos = strrpos($file, "(");
	if($start_pos) {
		$year = substr($file, $start_pos + 1, 4);
		if(!empty($year) && is_numeric($year) && $year > 1000) {
			$dir = $base_dir . "/" . $year . "/";
			$cmd = "scp '{$file}' {$host}:{$dir}";
			echo $cmd . PHP_EOL;
			exec($cmd, $output);
			var_dump($output);
		} else {
			echo "INVALID YEAR FOUND IN FILENAME: {$year} : {$file}" . PHP_EOL;
		}
	} else {
		echo "YEAR NOT FOUND IN FILENAME: {$file}" . PHP_EOL;
	}
}

?>
