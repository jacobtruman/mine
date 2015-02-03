<?php

$x = new SteamBots();

class SteamBots {
	protected $logins = array(
		"jacobtruman" => "trade_offers.js",
		"katytruman" => "trade_offers_dev.js",
		"granttruman" => "trade_offers_dev.js",
		"draketruman" => "trade_offers_dev.js",
		"gabrielletruman" => "trade_offers_dev.js",
		"logantruman" => "trade_offers_dev.js",
		"jwilltruman" => "trade_offers.js",
		"jacobwtruman" => "trade_offers_dev.js",
		"williamtruman" => "trade_offers_dev.js",
		"ivlostskitch" => "trade_offers_dev.js"
	);

	public function __construct() {
		$this->startBots();
		//$this->runTest();
	}

	protected function startBots() {
		$session_name = "steambots";
		exec("screen -AdmS {$session_name} -t controller bash");
		foreach($this->logins as $login => $script) {
			$cmd = "screen -S {$session_name} -X screen -t {$login} nodejs /mine/backup/nodejs_steambot/{$script} {$login}";
			exec($cmd);
		}
	}

	protected function runTest() {
		$session_name = "SteamBotsTest";
		exec("screen -AdmS {$session_name} -t tab0 bash");
		for($i = 0; $i < 3; $i++) {
			$cmd = "screen -S {$session_name} -p tab{$i} -X stuff $'echo test\necho test2\nbash\n'";
			echo $cmd.PHP_EOL;
			exec($cmd, $output);
			var_dump($output);
		}
	}
}

?>
