#!/usr/bin/php
<?php

require_once("/mine/phplib/DBConn.class.php");

$dir = "/mine/backup/Pictures";
$extensions = array("jpg", "png");
$imgCompare = new ImgCompare($dir, $extensions);
$filters = array("path"=>"camera", "path"=>"2014");
$imgCompare->findDuplicates($filters);

class ImgCompare {

	public $dir;
	protected $db;

	public function __construct($dir = NULL, $extensions = array()) {
		if($dir === NULL) {
			throw new Exception("The directory must be specified");
		} else if(!is_array($extensions) || count($extensions) < 1) {
			throw new Exception("At least one extension must be specified");
		}
		$this->dir = $dir;
		$this->extensions = $extensions;
		$this->db = new DBConn();
	}

	public function findDuplicates($filters = NULL) {
		$sql = "SELECT hash, count(*) FROM images";
		if($filters !== NULL && is_array($filters)) {
			foreach($filters as $column=>$value) {
				$wheres[] = $column." LIKE '%".$value."%'";
			}
			$sql .= " WHERE ".implode(" AND ", $wheres);
		}
		$sql .= " GROUP BY hash HAVING count(*) > 1";
		exit($sql.PHP_EOL);
		$result = $this->db->query($sql);
		while ($row = $result->fetch_array()) {
			list($hash, $count) = $row;
			$sql = "SELECT path FROM images WHERE hash = '{$hash}'";
			$res = $this->db->query($sql);
			while ($row2 = $res->fetch_array()) {
				list($path) = $row2;
				$duplicates[$hash][] = $path;
			}
		}
		print_r($duplicates);
	}

	public function buildDB() {
		$this->processDir($this->dir);
	}

	protected function processDir($dir) {
		$files = glob($dir."/*.{".implode(",", $this->extensions)."}", GLOB_BRACE);
		$this->processFiles($files);

		$dirs = glob($dir."/*", GLOB_ONLYDIR);
		if(count($dirs)) {
			foreach($dirs as $this_dir) {
				$this->processDir($this_dir);
			}
		}
	}

	protected function processFiles($files) {
		foreach($files as $file) {
			$hash = $this->db->real_escape_string(getFileHash($file));
			$file = $this->db->real_escape_string($file);
			echo $hash." :: ".$file.PHP_EOL;
			$sql = "INSERT IGNORE INTO images SET path = '{$file}', hash = '{$hash}'";
			$this->db->query($sql);
		}
	}

	protected function getFileHash($file) {
		return md5(file_get_contents($file));
	}
}

?>
