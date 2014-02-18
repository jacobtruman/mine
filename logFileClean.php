#!/usr/bin/php
<?php

require_once("DirectoryCleaner.class.php");

$c = new DirectoryCleaner("/scripts/logs");

$c->runProcess(".log");

?>
