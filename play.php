<?php 
/*
$result = '';

if (isset($_POST['vidID'])) {
	$vidHash = $_POST['vidID'];
	$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
	$filename = '';
	$caption = '';
	$media_title = '';
	$media_artist = '';
	$media_desc = '';
	$mediapath = '../media/';
	
	$xml = simplexml_load_file('mediainfo.xml');
	$item = $xml->Music;
	$id = cleanid($vidHash);
	
	foreach($item as $feed) {
		if($feed->ID == $id) {
			$filename = $feed->FileName;
			$caption = $feed->Caption;
			$media_title = $feed->Title;
			$media_artist = $feed->Artist;
			$media_desc = $feed->Description;
			$result = 'good';
			break;
		} else {
			$result = 'File not found';
		}
	}
	
	if($result!='good') {
		echo "<script> $('#loadingBar').modal('show'); </script>";
		
	} else {
		
		$title = $media_title . " - " . $media_artist;
		
		
		$str = <<<EOT
		 <script>
			$('.video').flowplayer({
			clip: {
			subtitles: [{
			"default": true,
			kind: "subtitles",
			srclang: "en",
			label: "English",
			src: "../media/caption/{$filename}.vtt"
			}],
			sources: [{ type: "video/mp4", src: "../media/{$filename}.mp4" }]
			},
			aspectRatio: "16:9",
			poster: "../media/img/{$filename}.jpg"
			});
		 </script>
EOT;
		$output = preg_replace($url, "<a href='$0' title='$0'>$0</a>", $media_desc);
		// Output of Player here;
		echo "<div class='row'><div class='container'><h1>[MV] {$title}</h1></div></div>";
		echo "<div class='row'><div class='col-lg-1'></div><div class='col-lg-10'>";
		echo "<div id='video fp-full'></div>";
		echo "</div><div class='col-lg-1'></div></div>";
		echo "<div class='row'><div class='container' id='post-content'>";
		echo "<h3>[MV] {$title}</h3><hr>";
		echo "<p>{$output}</p>";
		echo "</div></div>";
		echo $str;
	}
	
} else {
	$vidHash = 'none';
	echo "<script> $('#loadingBar').modal('show'); </script>";
}

function cleanid($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
*/
	
?>

<html>
<head>
<link rel='stylesheet' href='mediaelementplayer.min.css'>
</head>
<body>

<h1>Hello World</h1>

<div id='video'></div>

<script src='js/jquery.js'></script>
<script src='js/mediaelement.min.js'></script>
<script>

$('.video').mediaelementplayer({
	success: function(player,node) {
		setsrc([
			src: '../media/001.mp4',
			type: 'video/mp4'
		]);
		this.load();
	}
});

</script>
</body>
</html>