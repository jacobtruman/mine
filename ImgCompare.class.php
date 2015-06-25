#!/usr/bin/php
<?php

require_once("/mine/phplib/DBConn.class.php");

$args = getopt("bc");

$dir = "/mine/Pictures";
$extensions = array("jpg", "png");
$imgCompare = new ImgCompare($dir, $extensions);
if(isset($args['b'])) {
	$imgCompare->buildDB();
}
if(isset($args['c'])) {
	$filters = array("path"=>array("thumbnails", "KatyPhone"));
	$filters = array();
	$imgCompare->findDuplicates($filters);
}

class ImgCompare {

	public $dir;
	protected $db;
	protected $table = "images";

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
		$duplicates = array();
		$sql = "SELECT hash, count(*) FROM {$this->table}";
		if($filters !== NULL && is_array($filters)) {
			$wheres = array();
			foreach($filters as $column=>$value) {
				if(is_array($value)) {
					foreach($value as $val) {
						$wheres[] = $column." LIKE '%".$val."%'";
					}
				} else {
					$wheres[] = $column." LIKE '%".$value."%'";
				}
			}
			if(count($wheres)) {
				$sql .= " WHERE " . implode(" AND ", $wheres);
			}
		}
		$sql .= " GROUP BY hash HAVING count(*) > 1";

		$result = $this->db->query($sql);
		$size = 0;
		while ($row = $result->fetch_array()) {
			list($hash, $count) = $row;
			$sql = "SELECT path FROM {$this->table} WHERE hash = '{$hash}'";
			$res = $this->db->query($sql);
			while ($row2 = $res->fetch_array()) {
				list($path) = $row2;
				if(file_exists($path)) {
					$duplicates[ $hash ][] = $path;
					$size += filesize($path);
				} else {
					// remove the record from the db
					echo "Deleting record for {$path}".PHP_EOL;
					$sql = "DELETE FROM {$this->table} WHERE path = '{$this->db->real_escape_string($path)}'";
					$this->db->query($sql);
				}
			}
		}
		echo count($duplicates)." duplicates found".PHP_EOL;
		if(count($duplicates)) {
			//print_r($duplicates);
		}
		echo $size." Bytes".PHP_EOL;
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
			$hash = $this->db->real_escape_string($this->getFileHash($file));
			$file = $this->db->real_escape_string($file);
			echo $hash." :: ".$file.PHP_EOL;
			$sql = "INSERT IGNORE INTO {$this->table} SET path = '{$file}', hash = '{$hash}'";
			$this->db->query($sql);
		}
	}

	/*protected function getSize($path) {
		$bytes = sprintf('%u', filesize($path));

		if ($bytes > 0)
		{
			$unit = intval(log($bytes, 1024));
			$units = array('B', 'KB', 'MB', 'GB');

			if (array_key_exists($unit, $units) === true)
			{
				return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
			}
		}

		return $bytes;
	}*/

	protected function getFileHash($file) {
		return md5(file_get_contents($file));
	}
}

?>
