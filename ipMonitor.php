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

if(!empty($ip) && !empty($ip[0]) && $ip[0] !== $current_ip) {
	echo "IP Address changed from {$current_ip} to {$ip}" . PHP_EOL;
	file_put_contents($ip_file, $ip);
} else {
	echo "IP Address is still {$ip}" . PHP_EOL;
}

unset($ip);