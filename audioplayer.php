<?php 
$myCon = new mysqli("localhost", "root", "", "ea_data");
$items = array();

if($myCon->connect_error) {
	die("Connection failed: " . $myCon->connect_error);
}
$query = "SELECT * FROM audio";
$result = $myCon->query($query);

if($result->num_rows > 0) {
	while($output = $result->fetch_assoc()) {
		$items[] = $output;
	}
}
$myCon->close();
shuffle($items);
?>


<!DOCTYPE html>
<html>
<head>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/video-js.min.css" rel="stylesheet">
<style>
body {
	margin: 0;
	width: 350px;
	height: 410px;
	background: #17202A; /* Black */
}

#album-art, #play-info, #player-content {
	width: 350px;
}

#album-art, #album-art img {
	height: 350px;
	width: 350px;
	padding: 0;
}

#play-info {
	background: #424949;
	color: #F4F6F6;
	height: 20px;
	font-size: 12px;
	overflow: hidden;
}

</style>
</head>

<body>

<div>
	<div id="album-art"></div>
		 <div id="player"></div>  
	<div id="play-info"></div>
</div>



<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/player/jwplayer.js"></script>
<script src="js/player.js"></script>
<script>

// JWPLAYER REMOVE
jwplayer.key='5QW+dzm1wQvn5HOGmh5cF4j22BtPf5y0199vuQ==';
var listTag = document.getElementById("list");
var player = jwplayer("player");
player.setup({
width: "100%",
height: "40px",
autostart: true,
playlist: [<?php 
for($ctr = 0; $ctr < count($items); $ctr++) {
	$str = <<<EOT
{file: '{$items[$ctr]['file']}',
	image: '{$items[$ctr]['img']}',
	title: "{$items[$ctr]['title']}",
	mediaaid: '{$items[$ctr]['guid']}'
EOT;
	if($ctr >= count($items)-1) {
		echo $str . "}";	
	} else {
		echo $str . "},";	
	}
} 
?>]
});

player.on('playlistItem', function(e) {
	var titles = this.getPlaylistItem();
	var inx = this.getPlaylistIndex();
	//setTitle("Now Playing - " + titles.title);
	var contentDiv = document.getElementById("play-info");
	contentDiv.innerHTML = "<span>Now Playing: <strong>" + titles.title + "</strong></span>";
	$("#album-art").empty();
	$("<img src='" + titles.image + "' alt='" + titles.title + "' />").appendTo("#album-art");
	console.log("Now Index: " + inx);
	console.log("Next Up: " + titles.image );
});

player.on('ready', function(e) {
	//var playlistObj = this.getPlaylist();
	//var newPlaylist = shufflePlaylist(playlistObj);
	//this.load(newPlaylist);
});


function play(index) {
	player.playlistItem(index);
}

function fillPlaylist(playlist) {
	var title = document.getElementById("list");
	for(var i = 0; i < playlist.length; i++) {
		var titletext = document.createElement("H3");
		titletext.setAttribute("id","pl-" + i);
		titletext.setAttribute("onclick", "play(" + i + ")");
		titletext.appendChild(document.createTextNode(playlist[i].title));
		title.appendChild(titletext);
	}
}

function shufflePlaylist(playlist) {
	  var currentIndex = playlist.length, tempValue, randomIndex;

	  // While there remains elements to shuffle...
	  while (0 !== currentIndex) {
	    // Pick a remaining element...
	    randomIndex = Math.floor(Math.random() * playlist.length);
	    currentIndex -= 1;

	    // And swap it with the current element...
	    tempValue = playlist[currentIndex];
	    playlist[currentIndex] = playlist[randomIndex];
	    playlist[randomIndex] = tempValue;
	  }

	  return playlist;
}
// CHANGELOG: REMOVE JWPLAYER

$(document).ready(function() {
	$('body').contextmenu(function() {
		return false;
	});
});

</script>

</body>
</html>