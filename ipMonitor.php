#!/usr/local/bin/php
<?php

$ip_file = dirname(__FILE__) . "/.ip_address";

$cmd = "dig +short myip.opendns.com @resolver1.opendns.com";

if(file_exists($ip_file)) {
	$current_ip = trim(file_get_contents($ip_file));
} else {
	$current_ip = null;
}

exec($cmd, $output);

if(!empty($output) && !empty($output[0])) {
	$ip = trim($output[0]);
	if($ip !== $current_ip) {
		$msg = "IP Address changed from {$current_ip} to {$ip}";
		echo $msg . PHP_EOL;
		mail("jacob.truman@gmail.com", "IP Address Change", $msg);
		file_put_contents($ip_file, $ip);
	} else {
		echo "IP Address is still {$ip}" . PHP_EOL;
	}
} else {
	echo "Something went wrong; {$cmd} returned: " . print_r($output, true) . PHP_EOL;
}

unset($output);