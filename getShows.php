<?php

$cbs_shows_file = "./cbs_shows.json";
getCBSShows($cbs_shows_file);

$shows_file = "./shows.txt";
getShowsFromFile($shows_file);

function getCBSShows($cbs_shows_file) {
	if(file_exists($cbs_shows_file)) {
		$shows_to_get = json_decode(file_get_contents($cbs_shows_file), true);
		foreach ($shows_to_get as $show_info) {
			$show_id = $show_info['show_id'];
			$base_url = "http://www.cbs.com";

			for ($i = 1; $i <= $show_info['show_seasons']; $i++) {

				$show_url = "{$base_url}/carousels/shows/{$show_id}/offset/0/limit/30/xs/0/{$i}/";
				$data_file = "./{$show_id}-{$i}.json";

				if (!file_exists($data_file)) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $show_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);
					curl_close($ch);

					file_put_contents($data_file, $output);
				}

				$json = json_decode(file_get_contents($data_file), true);

				foreach ($json['result']['data'] as $record) {
					processUrl("{$base_url}{$record['url']}");
				}
			}
		}
	}
}

function getShowsFromFile($file) {
	if (file_exists($file)) {
		$urls = explode("\n", trim(file_get_contents($file)));
		foreach ($urls as $url) {
			if (!empty($url)) {
				processUrl($url);
			}
		}
	}
}

function processUrl($url) {
	$cmd = "youtube-dl --config-location ~/.config/youtube-dl/config-tvshow {$url}";
	echo $cmd . PHP_EOL;
	system($cmd);
}
