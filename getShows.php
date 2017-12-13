<?php
$shows_to_get = array(
    #array("show"=>"Frasier", "show_id"=>61456196, "show_seasons"=>11),
    array("show" => "Cheers", "show_id" => 61456194, "show_seasons" => 11),
    array("show" => "MacGyver NEW", "show_id" => 61456289, "show_seasons" => 2),
    array("show" => "MacGyver", "show_id" => 22924, "show_seasons" => 7),
    array("show" => "Star Trek Voyager", "show_id" => 216818, "show_seasons" => 7),
    array("show" => "Star Trek NG", "show_id" => 216813, "show_seasons" => 7),
    array("show" => "Star Trek Enterprise", "show_id" => 42721, "show_seasons" => 4),
    array("show" => "Star Trek Discovery", "show_id" => 61456261, "show_seasons" => 1),
    array("show" => "Star Trek OG", "show_id" => 22927, "show_seasons" => 3)
);
foreach ($shows_to_get as $show_to_get) {
    getShow($show_to_get);
}

function getShow($show_info)
{
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
            $cmd = "youtube-dl --config-location ~/.config/youtube-dl/config-tvshow {$base_url}{$record['url']}";
            echo $cmd . PHP_EOL;
            system($cmd);
        }
    }
}
