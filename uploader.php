<?php

$uploaddir = $audiofile = $imgfile = "";

if(!is_dir("../media/audio/tmp/")) {
	$uploaddir = "../media/audio/tmp/";
} else {
	mkdir("../media/audio/tmp/" , 0777);
	$uploaddir = "../media/audio/tmp/";
}
$audiofile = $uploaddir . basename($_FILES['audiofile']['name']);
$imgfile = $uploaddir . basename($_FILES['imgfile']['name']);

echo '<pre>';
/*
if (move_uploaded_file($_FILES['audiofile']['tmp_name'], $audiofile)) {
	if (move_uploaded_file($_FILES['imgfile']['tmp_name'], $imgfile)) {
		echo "File is valid, and was successfully uploaded.\n";
	} else {
		echo "Possible file upload attack1!\n";
	}
} else {
	echo "Possible file upload attack!\n";
}*/

echo 'Here is some more debugging info:';
print_r($_FILES);

echo "</pre>";


function convertMedia($file) {
	$newLoc = "../media/audio/";
	$ffmpegQuery = "ffmpeg -i {$file} -codec:a libmp3lame -qscale:a 2 {$newLoc}";
}

?>