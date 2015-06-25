#!/usr/bin/php
<?php

$options = array("v"=>"volume", "s"=>"season", "d"=>"disk");

$opts = getopt(implode("", array_map("addColon", array_keys($options))), array_values($options));

foreach($options as $key=>$option) {
	if(isset($opts[$key])) {
		$$options[$key] = $opts[$key];
	} else {
		throw new Exception("Must specify ".$options[$key]);
	}
}

$rip_dir = "/mine/RIP";
$shows_dir = "/mine/TVShows";
$show = "Everybody Loves Raymond";
$season_str = getSeasonString($season);

// map the season/disk/epsiode to the title on disk
$episode_mapping = array(
	6=>array(
		1=>array(
			1=>2,
			2=>3,
			3=>4,
			4=>5,
			5=>6
		),
		2=>array(
			6=>2,
			7=>3,
			8=>4,
			9=>5,
			10=>6
		),
		3=>array(
			11=>2,
			12=>3,
			13=>4,
			14=>5,
			15=>6
		),
		4=>array(
			16=>2,
			17=>3,
			18=>4,
			19=>5,
			20=>6
		),
		5=>array(
			21=>2,
			22=>3,
			23=>4,
			24=>5
		)
	),
	7=>array(
		1=>array(
			1=>2,
			2=>3,
			3=>4,
			4=>5,
			5=>6
		),
		2=>array(
			6=>2,
			7=>3,
			8=>4,
			9=>5,
			10=>6
		),
		3=>array(
			11=>2,
			12=>3,
			13=>4,
			14=>5,
			15=>6
		),
		4=>array(
			16=>2,
			17=>3,
			18=>4,
			19=>5,
			20=>6
		),
		5=>array(
			21=>2,
			22=>3,
			23=>4,
			24=>5
		)
	),
	8=>array(
		1=>array(
			1=>1,
			2=>2,
			3=>3,
			4=>4,
			5=>5
		),
		2=>array(
			6=>1,
			7=>2,
			8=>3,
			9=>4,
			10=>5
		),
		3=>array(
			11=>1,
			12=>2,
			13=>3,
			14=>4,
			15=>5
		),
		4=>array(
			16=>1,
			17=>2,
			18=>3,
			19=>4,
			20=>5
		),
		5=>array(
			21=>1,
			22=>2,
			23=>3
		)
	)
	,
	9=>array(
		1=>array(
			1=>1,
			2=>2,
			3=>3,
			4=>4
		),
		2=>array(
			5=>1,
			6=>2,
			7=>3,
			8=>4
		),
		3=>array(
			9=>1,
			10=>2,
			11=>3,
			12=>4
		),
		4=>array(
			13=>1,
			14=>2,
			15=>3,
			16=>4
		)
	)
);

foreach($episode_mapping[$season][$disk] as $episode=>$title) {
	if($title == 0) {
		throw new Exception("Title cannot be \"0\" - scan drive and fill in mapping array");
	}
	$episode = getEpisodeString($episode);
	$filename = "{$show} - {$season_str}{$episode}.m4v";
	$out_file = "{$rip_dir}/{$show} - {$season_str}{$episode}.m4v";
	$out_file_safe = escapeshellarg($out_file);
	$final_dir = "{$shows_dir}/{$show}/Season {$season}";
	if(!file_exists($final_dir)) {
		mkdir($final_dir, 0777, true);
	}
	$final_file = "{$final_dir}/{$filename}";
	if(!file_exists("{$final_file}")) {
		$cmd = "HandBrakeCLI -i /media/cdrom{$volume}/VIDEO_TS/ -o {$out_file_safe} -t {$title} --preset=Normal";
		echo $cmd.PHP_EOL;
		exec($cmd);
		echo "Moving {$out_file} to {$final_file}".PHP_EOL;
		rename($out_file, $final_file);
	} else {
		echo "File already exists: {$final_file}".PHP_EOL;
	}
}

function padNumber($num) {
	if($num <= 9) {
		$num = "0".$num;
	}
	return $num;
}

function getSeasonString($num) {
	return "S".padNumber($num);
}

function getEpisodeString($num) {
	return "E".padNumber($num);
}

function addColon($val) {
	return $val.":";
}

?>
