<?php

$tar_dir = "../media/";
$vid_dir = $tar_dir . basename($_FILES['vidfile']['name']);
$caption_dir = $tar_dir . "caption/" . basename($_FILES['captionfile']['name']);
$upload_stat = 1;
$vidFileType = pathinfo($vid_dir,PATHINFO_EXTENSION);
$captionFileType = pathinfo($caption_dir,PATHINFO_EXTENSION);



function convertMedia($fileLoc, $fileName) {
	$fileName = str_replace("tmp_", "", $fileName);
	$outputLoc = '../media/' . $fileName . '.mp4';
	$content = @file_get_contents('../media/tmp/log.txt');
	$result = "ffmpeg -hwaccel cuvid -c:v h264_cuvid -i $fileLoc -c:v h264_nvenc -r 23.98 -preset llhq -rc ll_2pass_quality -b:v 6000k -pass 1 -2pass -1 -profile:v high -level 4.0 $outputLoc 1> ../media/tmp/log.txt 2>&1";
	
	shell_exec($result);
	
	/*Generate Poster*/
	$posterimg = "ffmpeg -i $fileLoc -ss 00:00:10 -vframes 1 ../media/img/" . $fileName . ".jpg";
	shell_exec($posterimg);
	
	/*Generate Frames*/
	/*$frameFolder = "../media/img/cap" . $fileName;
	if(!file_exists($frameFolder)) {
		mkdir($frameFolder, 0777, true);
	}
	$frameimg = "ffmpeg -y -i $fileLoc -ss 00:00:10 -vf fps=1 -scale=320:-1 ../media/img/" . $fileName . ".jpg";
	shell_exec($frameimg); */
	
	if($outputLoc) {
		echo "Process complete";
		unlink($fileLoc);
	}
	/* FOR USE TO GET PROGRESS BAR
	if($content) {
		preg_match("/Duration: (.*?), start:/", $content, $matches);
		
		$rawDuration = $matches[1];
		$ar = array_reverse(explode(":", $rawDuration));
		$duration = floatval($ar[0]);
		if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
		if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;
		
		preg_match_all("/time=(.*?) bitrate/", $content, $matches);
		
		$rawTime = array_pop($matches);
		
		if (is_array($rawTime)){
			$rawTime = array_pop($rawTime);
		}
		
		$ar = array_reverse(explode(":", $rawTime));
		$time = floatval($ar[0]);
		if (!empty($ar[1])) $time += intval($ar[1]) * 60;
		if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;
		
		$progress = round(($time/$duration) * 100);
		
		echo "<br/>";
		echo "Duration: " . $duration . "<br>";
		echo "Current Time: " . $time . "<br>";
		echo "Progress: " . $progress . "%";
		
	} */
}



if(isset($_POST['submit'])) {
	/* DELETED Thumbnail */
	if($captionFileType != 'vtt' && $captionFileType != 'srt') {
		$upload_stat = 0;
		echo 'Caption not OK';
	} else {
		echo 'Caption OK';
	}
	
	if ($upload_stat == 0) {
		echo "Something went wrong while uploading file";
	} else {
		$newIMG = '001';
		$filename = glob("../media/*.mp4");
		$ctr = 0;
		
		do {
			$ctr++;
			if($ctr > 99) {
				$newIMG = $ctr;
			} elseif($ctr < 100 && $ctr > 9) {
				$newIMG = '0' . $ctr;
			} else {
				$newIMG = '00' . $ctr;
			}
		} while (file_exists($tar_dir . $newIMG . '.mp4'));
		
		//echo '<br><h2>Your new file name is ' . $tar_dir . $newIMG . '.mp4';
		
		$newLoc = $tar_dir . "tmp/tmp_" . $newIMG . "." . $vidFileType;
		$capLoc = $tar_dir . "tmp/tmp_" . $newIMG . "." . $captionFileType;
		
		if(move_uploaded_file($_FILES['vidfile']['tmp_name'], $newLoc)) {
			if(move_uploaded_file($_FILES['captionfile']['tmp_name'], "{$tar_dir}tmp/tmp_{$newIMG}.{$captionFileType}")) {
				echo "<p>Please wait while files are being converted.</p>";
				convertMedia($newLoc, "tmp_".$newIMG);
				convertCaption($capLoc, $newIMG, $captionFileType);
			} else {
				echo "Unable to upload file";
			}
		} else {
			echo "Unable to upload file";
		}
		
	}

}

?>