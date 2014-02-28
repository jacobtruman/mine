#!/usr/bin/php
<?
ini_set('display_errors', 1);
// Script to rename picture based on date taken value encoded into image

$year = "2013";

$path = "/mine/backup/Pictures/camera/".$year."/Nov";
$files = glob($path."/*.jpg");
foreach($files as $file)
{
	renameFile($file);
}

function renameFile($file)
{
	$exif = read_exif_data ($file);
	if($exif['Model'] === "NIKON D3200") {
		var_dump($file);
		var_dump($exif['Make']);
		var_dump($exif['Model']);
		$datetime = (isset($exif['DateTimeDigitized']) ? $exif['DateTimeDigitized'] : (isset($exif['DateTimeOriginal']) ? $exif['DateTimeOriginal'] : $exif['DateTime']));
		var_dump($datetime);
		exit;
		if(!empty($datetime))
		{
			$ts = strtotime($datetime);
			if(!empty($ts))
			{
				$new_file = getFilename($ts);
				echo "Renaming file ".$file." to ".$new_file."\n";
				#copy($file, $new_file);
				#rename($file, $new_file);
			}
		}
	}
}

function getFilename($ts)
{
	// format yyyy-mm-dd_hh'mm'ss
	$filename = date("Y-m-d_H'i's", $ts).".jpg";
	$year = date("Y", $ts);
	$month = date("M", $ts);
	$path = $year."/".$month;
	if(!is_dir($year))
		mkdir($year, 0777);
	if(!is_dir($path))
		mkdir($path, 0777);
	if(file_exists($path."/".$filename))
	{
		echo "file exists, trying again\n";
		return getFilename(++$ts, $path);
	}
	else
		return $path."/".$filename;
}

?>
