<?php

$cbs_shows_file = "./cbs_shows.json";
getCBSShows($cbs_shows_file);

$nbc_shows_file = "./nbc_shows.json";
getNBCShows($nbc_shows_file);

$shows_file = "./shows.txt";
getShowsFromFile($shows_file);

function getCBSShows($shows_file, $latest = true) {
	if($latest) {
		$limit = 1;
	} else {
		$limit = 100;
	}
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
				if($latest) {
					$data_file = "./{$show_id}-latest.json";
				} else {
					$data_file = "./{$show_id}-{$offset}.json";
				}

				populateDataFile($show_url, $data_file);

				$json = json_decode(file_get_contents($data_file), true);
				if($total == null) {
					$total = $json['result']['total'];
				}
				$offset += $limit;

				foreach ($json['result']['data'] as $record) {
					$file_path = null;
					$episode_number = $record['episode_number'];
					if(strstr($episode_number, ",")) {
						$episode_numbers = explode(",", $episode_number);
						$episodes = array();
						foreach($episode_numbers as $episode_number) {
							$episodes[] = "E" . str_pad(trim($episode_number), 2, "0", STR_PAD_LEFT);
						}
						$episode_string = implode("-", $episodes);
						$file_path = "/mine/TVShows/%(series)s/Season %(season_number)s/%(series)s - S%(season_number)02d{$episode_string}";
					}
					processUrl("{$base_url}{$record['url']}", $file_path);
				}

				unlink($data_file);

				if($latest) {
					break;
				}
			}
		}
	}
}

function getNBCShows($shows_file, $latest = true) {
	if(file_exists($shows_file)) {
		$shows_to_get = json_decode(file_get_contents($shows_file), true);
		foreach ($shows_to_get as $show_info) {
			if(!isset($show_info['active']) || !$show_info['active']) {
				continue;
			}
			$show_id = $show_info['show_id'];
			$show_title = $show_info['show_title'];

			$base_url = "https://api.nbc.com/v3.14/videos";

			$start_date = date("Y-m-d", strtotime("-1 month"));
			$end_date = date("Y-m-d");

			$params = array();
			$params[] = "fields[videos]=title,type,available,seasonNumber,episodeNumber,expiration,entitlement,tveAuthWindow,nbcAuthWindow,permalink,embedUrl";
			$params[] = "filter[show]={$show_id}";
			if($latest) {
				$params[] = "filter[available][value]={$start_date}";
				$params[] = "filter[available][value]={$end_date}";
				$params[] = "filter[available][operator]=BETWEEN";
			} else {
				$params[] = "filter[available][value]={$end_date}";
				$params[] = "filter[available][operator]=<=";
			}

			$params[] = "filter[expiration][value]={$end_date}";
			$params[] = "filter[expiration][operator]=>";
			$params[] = "filter[entitlement][value]=free";
			$params[] = "filter[entitlement][operator]==";
			$params[] = "filter[type][value]=Full Episode";
			$params[] = "filter[type][operator]==";
			$params[] = "sort=-airdate";

			$params_string = str_replace("%5D%3D", "%5D=", str_replace("%3D%3C", "=%3C", str_replace("%3D%3E", "=%3E", str_replace("%3D%3D", "=%3D", implode("&", array_map("urlencode", $params))))));

			$show_url = $base_url . "?" . $params_string;

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

				$get = false;
				foreach($attributes['nbcAuthWindow'] as $window) {
					if($window['type'] != "free") {
						continue;
					}
					$end_ts = strtotime($window['end']);
					if($now < $end_ts) {
						$get = true;
					}
				}

				if($get) {
					$season_number = $attributes['seasonNumber'];
					$episode_number = $attributes['episodeNumber'];
					$season = str_pad($season_number, 2, "0", STR_PAD_LEFT);
					$episode = str_pad($episode_number, 2, "0", STR_PAD_LEFT);
					$episode_string = "S{$season}E{$episode}";
					$file_path = "/mine/TVShows/{$show_info['show_title']}/Season {$season_number}/{$show_info['show_title']} - {$episode_string}";

					processUrl($attributes['permalink'], $file_path);
					if($latest) {
						break;
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
