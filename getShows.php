<?php

$cbs_shows_file = "./cbs_shows.json";
getCBSShows($cbs_shows_file);

$nbc_shows_file = "./nbc_shows.json";
getNBCShows($nbc_shows_file);

$shows_file = "./shows.txt";
getShowsFromFile($shows_file);

function getCBSShows($shows_file) {
	$limit = 100;
	if(file_exists($shows_file)) {
		$shows_to_get = json_decode(file_get_contents($shows_file), true);
		foreach ($shows_to_get as $show_info) {
			$offset = 0;
			$total = null;

			while($total == null || $offset <= $total) {
				if (!isset($show_info['active']) || !$show_info['active']) {
					echo "{$show_info['show_title']} is not active - skipping" . PHP_EOL;
					break;
				}
				$show_id = $show_info['show_id'];
				$base_url = "http://www.cbs.com";

				$show_url = "{$base_url}/carousels/shows/{$show_id}/offset/{$offset}/limit/{$limit}/";
				$data_file = "./{$show_id}-{$offset}.json";

				populateDataFile($show_url, $data_file);

				$json = json_decode(file_get_contents($data_file), true);
				if($total == null) {
					$total = $json['result']['total'];
				}
				$offset += $total;

				foreach ($json['result']['data'] as $record) {
					processUrl("{$base_url}{$record['url']}");
				}

				unlink($data_file);
			}
		}
	}
}

function getNBCShows($shows_file) {
	if(file_exists($shows_file)) {
		$shows_to_get = json_decode(file_get_contents($shows_file), true);
		foreach ($shows_to_get as $show_info) {
			if(!isset($show_info['active']) || !$show_info['active']) {
				continue;
			}
			$show_id = $show_info['show_id'];
			$show_title = $show_info['show_title'];
			$show_url = "https://api.nbc.com/v3.14/videos?fields%5Bvideos%5D=title%2Ctype%2Cavailable%2CseasonNumber%2CepisodeNumber%2Cexpiration%2Centitlement%2CtveAuthWindow%2CnbcAuthWindow%2Cpermalink%2CembedUrl&fields%5Bshows%5D=description%2Cname%2CshortDescription%2CshortTitle&fields%5Bimages%5D=derivatives%2Cpath%2Cwidth&fields%5Bseasons%5D=seasonNumber%2CcontestantTitle&fields%5BgenereticProperties%5D=showCollection.collections&include=show%2Cshow.season%2Cshow.iosProperties.compactImage%2Cshow.genereticProperties.showCollection.collections&derivatives=landscape.widescreen.size640.x1&filter%5Bshow%5D={$show_id}&filter%5Bavailable%5D%5Bvalue%5D=2017-12-14T12%3A30%3A00-05%3A00&filter%5Bavailable%5D%5Boperator%5D=%3C%3D&filter%5Btype%5D%5Bvalue%5D=Full%20Episode&filter%5Btype%5D%5Boperator%5D=%3D&sort=-airdate";

			$data_file = "./" . str_replace(" ", "_", strtolower($show_title)) . ".json";

			populateDataFile($show_url, $data_file);

			$json = json_decode(file_get_contents($data_file), true);

			$now = time();
			foreach($json['data'] as $record) {
				$attributes = $record['attributes'];
				$entitlement = $attributes['entitlement'];
				if($entitlement != "free") {
					continue;
				}

				foreach($attributes['nbcAuthWindow'] as $window) {
					if($window['type'] != "free") {
						continue;
					}
					$end_ts = strtotime($window['end']);
					if($now < $end_ts) {
						$season_number = $attributes['seasonNumber'];
						$episode_number = $attributes['episodeNumber'];
						$season = str_pad($season_number, 2, "0", STR_PAD_LEFT);
						$episode = str_pad($episode_number, 2, "0", STR_PAD_LEFT);
						$episode_string = "S{$season}E{$episode}";
						$file_path = "/mine/TVShows/{$show_info['show_title']}/Season {$season_number}/{$show_info['show_title']} - {$episode_string}";

						processUrl($attributes['permalink'], $file_path);
					}
				}
			}

			unlink($data_file);
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

function populateDataFile($url, $file) {
	if (!file_exists($file)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		file_put_contents($file, $output);
	}
}

function processUrl($url, $file_path = null) {
	$cmd = "youtube-dl --config-location ~/.config/youtube-dl/config-tvshow";
	if(!empty($file_path)) {
		$cmd .= " -o '{$file_path}.%(ext)s'";
	}
	$cmd .= " {$url}";
	echo $cmd . PHP_EOL;
	system($cmd);
}
