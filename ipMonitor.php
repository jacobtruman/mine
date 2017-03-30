#!/usr/local/bin/php
<?php

$ip_file = "./.ip_address";

$cmd = "dig +short myip.opendns.com @resolver1.opendns.com";

if(file_exists($ip_file)) {
	$current_ip = file_get_contents($ip_file);
} else {
	$current_ip = null;
}

exec($cmd, $ip);

if($ip !== $current_ip) {
	file_put_contents($ip_file, $ip);
}

unset($ip);