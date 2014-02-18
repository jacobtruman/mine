<?

//$base_dir = "/backup/Sync/Videos";
$base_dir = "/chell/d/Videos";
$folders = glob($base_dir."/*");

foreach($folders as $folder)
{
	if(is_dir($folder))
	{
		$folder_parts = explode("/", $folder);
		$show = end($folder_parts);
//		echo "Show: ".$show."\n";
		$files = getFiles($folder);
		renameFiles($files, $show);
	}
}

function getFiles($dir)
{
	$files = glob($dir."/*");
	return $files;
}

function renameFiles($files, $show, $season)
{
	foreach($files as $file)
	{
		if(is_dir($file))
		{
			$folder_parts = explode("/", $file);
			$season = end($folder_parts);
//			echo "\tSeason: ".$season."\n";
			$file_list = getFiles($file);
			renameFiles($file_list, $show, $season);
		}
		else
		{
			renameFile($file, $show, $season);
		}
	}
}

function renameFile($file, $show, $season)
{
	global $base_dir;
	$file_parts = explode("/", $file);
	$filename = end($file_parts);
	$filename_parts = explode(".", $filename);
	$ext = end($filename_parts);
	if($filename == "Thumbs.db")
	{
		unlink($filename);
		echo "Deleting ".$file."\n";
	}

	$season_pattern = "S[0-9]{2}";
	$episode_pattern = "E[0-9]{2}";
	$full_pattern = $show." - ".$season_pattern.$episode_pattern;
	if(!preg_match("/".$full_pattern."/", $filename, $matches))
	{
		unset($matches);
		if(preg_match("/".$season_pattern.$episode_pattern."/", $filename, $matches))
		{
			$full_episode = $matches[0];
			if(empty($season))
			{
				$season = "Season ".ltrim(substr($full_episode, 1, 2), '0');
			}
			$new_file = $base_dir."/".$show."/".$season."/".$show." - ".$full_episode.".".$ext;
		}
		else
		{
			$filename = str_replace(".", " ", $filename);
			$filename_parts = explode(" ", $filename);
			$ext = end($filename_parts);
			$full_episode = $filename_parts[0];
			if(strlen($filename_parts[0]) == 4)
			{
				$full_episode = "S".substr($full_episode, 0, 2)."E".substr($full_episode, 2, 2);
			}
			else
			{
				$full_episode = "S0".$full_episode[0]."E".substr($full_episode, 1, 2);
			}
			$new_file = $base_dir."/".$show."/".$season."/".$show." - ".$full_episode.".".$ext;
		}
		if(isset($new_file) && !empty($new_file))
		{
			$file = str_replace($filename, '"'.$filename.'"', $file);
			$new_file = addslashes($new_file);
			$cmd = "mv ".str_replace(" ", "\ ", $file)." ".str_replace(" ", "\ ", $new_file);
			echo $cmd."\n";
			exec($cmd);
		}
	}
}

?>
