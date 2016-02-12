<?php

$dir = "/mine/Movies";

$exts = array("mp4", "m4v", "avi", "srt");

if (is_dir($dir)){
	if ($dh = opendir($dir)){
		while (($file = readdir($dh)) !== false){
			$info = pathinfo($file);
			if(isset($info['extension']) && in_array($info['extension'], $exts)) {
				$filename = $info['filename'];
				preg_match('#\((.*?)\)#', $filename, $match);
				if(!isset($match[1])) {
					//echo $file . PHP_EOL;
				} else {
					$year = $match[1];
					//echo $file . " :: " . $year . PHP_EOL;
					$this_dir = $dir."/".$year;
					if(is_dir($this_dir)) {
						$file = addslashes($file);
						//echo $this_dir." already has a dir".PHP_EOL;
						$cmd = "mv '{$dir}/{$file}' '{$this_dir}/{$file}'";
						echo $cmd.PHP_EOL;
						exec($cmd);
					} else {
						echo "Making dir: {$this_dir}".PHP_EOL;
						mkdir($this_dir, 0777, true);
					}
				}
			}
		}
		closedir($dh);
	}
}

?>
