<?

require_once("TMDb.class.php");

$url = "http://api.themoviedb.org/3/search/movie";

$api_key = "be1a6ca1aac91baeb5d3b3eb303bf8b2";

$query = "the dark knight rises";

$tmdb = new TMDb($api_key, 'en', TRUE);

$config = $tmdb->getConfig();

$return = $tmdb->searchMovie($query);

var_dump($return);

$imagetype = "jpg";
//$sizes = $tmdb->getAvailableImageSizes($imagetype);
//var_dump($sizes);
//exit;
var_dump($tmdb->getImageUrl($return['results'][0]['poster_path'], $imagetype, "w600"));

?>
