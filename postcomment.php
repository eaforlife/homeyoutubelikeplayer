<?php
session_start();
if(isset($_POST['comment']) && isset($_POST['guid'])) {
	$username = '';
	if(isset($_SESSION['user_name'])) {
		$username = $_SESSION['user_name'];
	} else {
		$rng = rand(10000,99999);
		$username = "anonymous" . $rng;
	}
	
	$comment = $_POST['comment'];
	$videoID = $_POST['guid'];
	
	if(!empty($comment) && !empty($videoID)) {
		$conn = new mysqli("localhost", "root", "", "ea_data");
		if($conn->error) die("Connection to database failed: " . $conn->error);
		$query = $conn->prepare("INSERT INTO comments (user, comment, vid_id) VALUES (?,?,?);");
		$query->bind_param("sss",$username,$comment,$videoID);
		$comment = nl2br($comment);
		$comment = htmlspecialchars($comment);
		$username = stripslashes($username);
		$username = htmlspecialchars($username);
		$videoID = stripslashes($videoID);
		$videoID = htmlspecialchars($videoID);
		$query->execute();
		$query->close();
		$conn->close();
	}
	
}

?>