<?php
session_start();

if(isset($_SESSION['user_name']) && isset($_SESSION['user_level'])) {
	$user_name = $_SESSION['user_name'];
	$user_level = $_SESSION['user_level'];
} else {
	$user_level = 3;
	$user_name = "";
}

/** Other page stuff here **/

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<style>
		body {
			margin: 0;
			padding-top: 60px;
		}
		
		#myContainer {
			min-height: 600px;
		}
		
		.progress {
		 margin: 0 auto;
		 width: 80%;
		}
	</style>
</head>
<body>
<!-- NAVBAR TOP -->
<div class="navbar-wrapper">
	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>                        
	      </button>
	      <a class="navbar-brand" href="#">EA Local</a>
	    </div>
	    
	    <div class="collapse navbar-collapse" id="menu">
	      <ul class="nav navbar-nav">
	        <li><a href="./?p=home" id="home-nav">Home</a></li>
	        <li><a href="./?p=reddit" id="reddit-nav">Reddit</a></li>
	        <li><a href="./?p=news" id="news-nav">News</a></li>
	      </ul>
	      <span class="navbar-text" id="dt"></span>
	      <ul class="nav navbar-nav navbar-right">
		      <li><a class="radio-popup" title="Radio"><span class="glyphicon glyphicon-music" aria-hidden="true"></span></a></li>
		      <?php if($user_level == 3): ?>
			      <li><a href="#" data-toggle="modal" data-target="#modal-signup"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Sign Up</a></li>
			      <li><a href="#" data-toggle="modal" data-target="#modal-login"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</a></li>
			   <?php else: ?>
			   	  <li><span class="navbar-text">Welcome <?php echo strtoupper($user_name); ?></span></li>
			   	  <li class="active"><a href="#" class="btn" id="menu" title="Personalize"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></li>
			      <li><a href="./logout" data-toggle="modal" id="logout" title="Log Out"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
			   <?php endif; ?>
		   </ul>
	    </div>
	  </div>
	</nav>
</div>

<?php if($user_level == 3): ?>
	<!-- Login/Sign Up -->
	<div class="modal fade" id="modal-login" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Login</h4>
				</div>
				<div class="modal-body">
					<form method="POST" action="login.php" id="login">
						<input type="hidden" value="login" name="type" />
						<div class="form-group">
							<label for="usn" class="sr-only">Username:</label>
							<input type="text" class="form-control" name="usn" id="usn" placeholder="Username" />
						</div>
						<div class="form-group">
							<label for="pwd" class="sr-only">Password:</label>
							<input type="password" class="form-control" name="pwd" id="pwd" placeholder="Password" />
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary" id="login-submit">Login</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-signup" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Sign Up</h4>
				</div>
				<div class="modal-body">
					<form method="POST" action="login.php" id="signup">
						<input type="hidden" value="signup" name="type" />
						<div class="form-group">
							<label for="new-usn" class="sr-only">Username:</label>
							<input type="text" class="form-control" name="new-usn" id="new-usn" placeholder="Username" />
							
						</div>
						<div class="form-group">
							<label for="new-pwd" class="sr-only">Password:</label>
							<input type="Password" class="form-control" name="new-pwd" id="new-pwd" placeholder="Password" />
						</div>
						<div class="form-group">
							<label for="new-email" class="sr-only">E-Mail:</label>
							<input type="text" class="form-control" name="new-email" id="new-email" placeholder="E-Mail" />
							
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary" id="create-submit">Create</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- End NAVBAR -->

<!-- Page content below -->

<div class="col-md-12"><!-- Line Break -->&nbsp;</div>
<?php if($user_level >= 3): ?>
	<div class="container">
	
		<H3>Sorry!</H3>
		<p><strong>You have to log in to access this page!</strong></p>
	</div>
<?php else: ?>
<div class="container" id="myContainer">
	<div class="col-md-2">
	<!-- SIDE MENU HERE -->
	<ul class="nav nav-pills nav-stacked" id="sideMenu">
		<?php if($user_level == 1): ?>
		<li role="presentation"><a href="#" id="uploadvid"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> Upload Video</a></li>
		<?php endif; ?>
		<li role="presentation"><a href="#" id="uploadaudio"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> Upload Audio</a></li>
		<?php if($user_level == 1): ?>
		<li role="presentation"><a href="#" id="audioedit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Playlist Editor (Audio)</a></li>
		<li role="presentation"><a href="#" id="videoedit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Playlist Editor (Video)</a></li>
		<?php endif; ?>
		<li role="presentation"><a href="#" id="newplaylist"><span class="glyphicon glyphicon-music" aria-hidden="true"></span> Create playlist</a></li>
		<li role="presentation"><a href="#" id="account"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Account</a></li>
	</ul>
	</div>
	
	<div class="col-md-10">
			<?php if(isset($_SESSION['result']) && !empty($_SESSION['result'])): ?>
			<div class="panel panel-info" id="load-bar">
				<?php if(isset($_GET['s']) && $_GET['s']=='success'): ?>
					<div class="panel-body">
						<strong>Uploading Success!</strong>
						<p>You may view the logs below</p>
						<pre><?php echo $_SESSION['result']; ?></pre>
						<div class="progress">
							<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" style="width: 100%">
							Your file(s) will be available shortly...
						  	</div>
						</div>
						<br />
						<pre><?php echo $_SESSION['result']; ?></pre>
					</div>
				<?php else: ?>
					<div class="panel-body">
						<strong>Something went wrong</strong>
						<p>You may view the logs below</p>
						<div class="progress">
							<div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="100" style="width: 100%">
							Upload failed...
						  	</div>
						</div><br />
						<pre><?php echo $_SESSION['result']; ?></pre>
					</div>
					
				<?php endif; ?>
			</div>
			<?php else: ?>
			<div class="panel panel-info" id="load-bar">
				<div class="panel-body">
					<strong>Welcome <?php if($user_level < 3) {
						echo $user_name;
					} ?>!</strong><br />
					<p>Please select from the left navigation to proceed!</p>
					<?php if($user_level != 1): ?>
					<p>Notice: Some options are not available on user level.</p>
					<?php endif; ?>
					<div class="progress">
						<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" style="width: 100%">
						Waiting for input...
					  	</div>
					</div>
					
				</div>
				
			</div>
			<?php endif; ?>
			
			<div id="content">
				<!-- CONTENT HERE -->
			</div>
	</div>
</div>
<?php endif; ?>
<!-- END OF PAGE CONTENT -->

<footer class="text-center">
	<a class="up-arrow" href="#top" data-toggle="tooltip" title="TO TOP">
	    <span class="glyphicon glyphicon-chevron-up"></span>
	</a><br />
	<p>EA Local &copy; 2017 <small>Scripting and design by EA</small></p>
</footer>

<!-- Scripts here for fast load time -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
console.log("<?php echo $user_level; ?>");
var loadedDoc = "";

$(document).ready(function() {

	$(".radio-popup").click(function() {
		player.pause(true);
		if(radio && !radio.closed) {
			radio.focus();
		} else {
			radio = window.open("audioplayer","Music Player","status=0,resizable=0,scrollbars=0,width=350,height=410");
		}
	});
	<?php if($user_level >= 3): ?>
	// Not Logged-In
		$("#login-submit").click(function() {
			$("#login").submit();
		});
		$("#create-submit").click(function() {
			$("#signup").submit();
		});
		$("#login input").keypress(function(e) {
		    if(e.which == 13) {
		    	$("#login").submit();
		    }
		});
		$("#signup input").keypress(function(e) {
		    if(e.which == 13) {
		    	$("#signup").submit();
		    }
		});
	<?php endif; ?>
	
	<?php if($user_level < 3): ?>
	// Logged-In
	$("#logout").click(function() {
		<?php 
		echo "window.location.href = 'admin1.php';\n";
		?>
	});
	
	$("#sideMenu").on("click","li", function() {
		$("#sideMenu li").removeClass("active disabled");
		$(this).addClass("active disabled");
	});

	$("#uploadvid").click(function() {
		$("#content").empty();
		$("#load-bar").hide().fadeOut('1000');
		$("#content").hide().load("uploadvideo.php").fadeIn('1500');
		$(document).prop('title', 'Upload Video');
	});
	$("#uploadaudio").click(function() {
		$("#content").empty();
		$("#load-bar").hide().fadeOut('1000');
		$("#content").hide().load("uploadaudio.php").fadeIn('1500');
		$(document).prop('title', 'Upload Audio');
		return false;
	});
	$("#audioedit").click(function() {
		$("#content").empty();
		$("#load-bar").hide().fadeOut('1000');
		$(document).prop('title', 'Modify Audio Library');
	});
	$("#videoedit").click(function() {
		$("#content").empty();
		$("#load-bar").hide().fadeOut('1000');
		$(document).prop('title', 'Modify Video Library');
	});
	$("#newplaylist").click(function() {
		$("#content").empty();
		$("#load-bar").hide().fadeOut('1000');
		$(document).prop('title', 'Personalize Playlist');
	});
	$("#account").click(function() {
		$("#content").empty();
		$("#load-bar").hide().fadeOut('1000');
		//Testing audio player:
		//openPlayer();
		//End of audio player;
		$(document).prop('title', 'Account Settings');
	});
	<?php endif; ?>

	// Common Jquery Menus
	
});


function openPlayer() {
	var playerWindow = window.open('audioplayer.php', 'Audio Player', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=350, height=410');
}

</script>

</body>
</html>
<?php 
if(!empty($_SESSION['result']) && isset($_SESSION['result'])) {
	unset($_SESSION['result']);	
}

?>