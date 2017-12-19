<?php

require_once("TVShowFetch.class.php");

$tvsf = new TVShowFetch();

$tvsf->getCBSShows("./cbs_shows.json");

$tvsf->getNBCShows("./nbc_shows.json");

$tvsf->getCWShows("./cw_shows.json");

$tvsf->getShowsFromFile("./shows.txt");