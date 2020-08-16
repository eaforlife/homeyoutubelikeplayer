<?php
session_start();
if(isset($_SESSION['result'])) {
	unset($_SESSION['result']);
}

if(isset($_SESSION['user_name']) && isset($_SESSION['user_level'])) {
	$rng = rand(10000,99999);
	$_SESSION['user_name'] = "anonymous".$rng;
	unset($_SESSION['user_level']);
}

header('Refresh: 5;url=./');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Please Wait...</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<style>
	body {
		margin:0;
		padding:0;
		padding-top: 150px;
		background: #E5E8E8;
	}
	
	.content {
		padding-top: 50px;
		min-height: 200px;
		background: #F8F9F9;
	}
	
	.progress {
		margin: 0 auto;
		width: 80%;
	}
	</style>
</head>
<body>

<div class="container">
	<div class="content">
		<h4 class="text-center">Redirecting you shortly...</h4>
		<div class="progress">
			<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" style="width: 100%">
			Successfully logged out...
		  	</div>
		</div>
	</div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>