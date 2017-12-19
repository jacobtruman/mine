<?php

require_once("TVShowFetch.class.php");

$tvsf = new TVShowFetch();

$cbs_shows_file = "./cbs_shows.json";
$tvsf->getCBSShows($cbs_shows_file);

$nbc_shows_file = "./nbc_shows.json";
$tvsf->getNBCShows($nbc_shows_file);

$shows_file = "./shows.txt";
$tvsf->getShowsFromFile($shows_file);