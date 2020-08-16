<?php
session_start();
if(!empty($_GET['guid'])) {
	$myCon = new mysqli("localhost", "root", "", "ea_data");
	
	if($myCon->connect_error) {
		die("Connection failed: " . $myCon->connect_error);
	}
	$query = "SELECT * FROM comments WHERE vid_id='" . $_GET['guid'] . "' ORDER BY timestamp DESC";
	$result = $myCon->query($query);
	if($result->num_rows > 0) {
		while($output = $result->fetch_assoc()) {
			echo "<div class='row'>";
			echo "<h6>".$output['user']."&nbsp;<small>".getInterval($output['timestamp'])."</small></h6>";
			echo "<p>".htmlspecialchars_decode($output['comment'])."</p>";
			echo "</div>";
		}
	} else {
		$emptyComment = <<<EOT
			<div class="row">&nbsp;</div>
			<div class="row"><div class="col-xs-4 col-xs-push-4"><p class="muted">No comments in this video</p></div></div>
			<div class="row">&nbsp;</div>
			<div class="row">&nbsp;</div>
EOT;
		echo $emptyComment;
	}
	$myCon->close();
} else {
	$emptyComment = <<<EOT
			<div class="row">&nbsp;</div>
			<div class="row"><div class="col-xs-4 col-xs-push-4"><p class="muted">No comments in this video</p></div></div>
			<div class="row">&nbsp;</div>
			<div class="row">&nbsp;</div>
EOT;
	echo $emptyComment;
}

function getInterval($data) {
	date_default_timezone_set('Asia/Manila');
	$currentDate = new DateTime('now');
	$postDate = new DateTime($data);
	$date = strtotime($data);
	$date = date("D M d Y, g:i:sA", $date);
	$interval = $currentDate->diff($postDate);
	$hours = $interval->h;
	$hours = $hours + ($interval->days*24);
	$diff = '';
	if($hours > 0 && $hours <= 1) {
		$diff = $hours . ' hour ago';
	} else if($hours > 1) {
		$diff = $hours . ' hours ago';
	} else {
		$diff = 'less than an hour ago';
	}
	return $diff;
}
?>