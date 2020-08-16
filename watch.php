<?php 
/* RECOMMENDED HEADER */
session_start();
$guid = $filename = $title = $artist = $description = $caption = $timestamp = "";
$error = "";
$media_dir = "../media/";
$radio_pop = "Music";

/* MIN WIDTH 655 */
/* WIDTH 1020 */
/* RELATED BAR: 445PX */
/* LEFT CONTAINER WIDTH: */
unset($_SESSION['result']);
if(isset($_SESSION['user_name']) && isset($_SESSION['user_level'])) {
	$user_name = $_SESSION['user_name'];
	$user_level = $_SESSION['user_level'];
} else {
	$rng = rand(10000,99999);
	$user_level = 3;
	$user_name = "anonymous".$rng;
	$_SESSION['user_name'] = $user_name;
}
$video_ready = 0;
/* END OF RECOMMENDED HEADER */

/* Capture Video */
if(isset($_GET['v'])) {
	
	$id = htmlspecialchars($_GET['v']);
	$query = "SELECT * FROM video WHERE guid = ?";
	$myCon = new mysqli('localhost', 'root', '', 'ea_data');
	$search = $myCon->prepare($query);
	$search->bind_param("s", $mediaaid);
	$mediaaid = $id;
	$search->execute();
	$saferesult = $search->get_result();
	while($output = $saferesult->fetch_assoc()) {
		if($mediaaid == $output['guid']) {
			$guid = $output['guid'];
			$filename = $output['file'];
			$title = $output['title'];
			$artist = $output['artist'];
			$description = $output['description'];
			$caption = $output['caption'];
			$timestamp = $output['timestamp'];
			break;
		} else {
			$guid = "";
		}
	}
	$error .= mysqli_error($myCon);
	$search->close();
	$myCon->close();
	if($guid=="") {
		$error .= "Video Not Found<br />";
	}
	
}
$test = glob("../media/tmp/tmp_{$filename}.*");
if(!empty($test) || $guid=="") {
	$video_ready = 0;
} else {
	$video_ready = 1;
}

?>


<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>[Now Playing]<?php echo $title. " - " . $artist; ?></title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/static.css" rel="stylesheet">
	<link href="css/video-js.min.css" rel="stylesheet">
	<link href="css/video-reso.css" rel="stylesheet">
	<style>
		body {
			margin: 0;
			padding-top: 60px;
			background: #E5E8E8; /* Dirty White */
		}
		.comment-content {
		 padding-left: 20px;
		 padding-right: 20px;
		}
	</style>
</head>
<body id="top">
<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>                        
	      </button>
	      <a class="navbar-brand" href="./">EA Local</a>
	    </div>
	    
	    <div class="collapse navbar-collapse" id="menu">
	     	<?php /*CHANGELOG: REMOVE NAVBAR IN WATCH MODULE*/ ?>
			<ul class="nav navbar-nav navbar-right">
			<?php if($user_level == 3): ?>
		      	<li><a href="#" data-toggle="modal" data-target="#modal-signup"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Sign Up</a></li>
		      	<li><a href="#" data-toggle="modal" data-target="#modal-login"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</a></li>
		  	<?php else: ?>
			   	  <li><span class="navbar-text">Welcome <?php echo strtoupper($user_name); ?></span></li>
			   	  <li><a href="./admin1" class="btn" id="menu" title="Personalize"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></li>
			      <li><a href="./logout" data-toggle="modal" id="logout" title="Log Out"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
		  	<?php endif; ?>
		    </ul>
		   
	    </div>
	  </div>
</nav>

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

<!-- Page content below -->
<div class="watch-container">
	<?php if($video_ready==0): ?>
	<div class="left-panel">
		<div class="video-container">
			<div class="video-notready text-center text-info">
				<?php if($error==''): ?>
				<h2>Video is not ready</h2>
				<p>Processing the video might take awhile. You can check back later!</p>
				<div class="progress">
				  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" style="width:100%">
				    <span>Processing video..</span>
				  </div>
				</div>
				<?php else: ?>
				<h2>Error 404</h2>
				<p><?php echo $error; ?></p>
				<div class="progress">
				  <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style="width:100%">
				    <span>File Not Found</span>
				  </div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="title-container padding">
			<h3><?php echo $title; ?>  <small><?php echo $artist; ?></small></h3>
		</div>
		<div class="share-container padding"><!-- Only Show after video play complete! --></div>
		<div class="description-container padding">
			<strong>Published on <?php echo date('M j, Y', strtotime($timestamp)); ?></strong><br />
			<p class="text-justify"><?php echo $description; ?></p>
		</div>
		
	</div>
	<?php else: ?>
	<div class="left-panel">
		<div class="video-container">
			<!-- REMOVED JWPLAYER: <div id="player"></div> -->
			<video id="player" class="video-js vjs-big-play-centered" poster="<?php echo "{$media_dir}img/$filename.jpg"; ?>" preload="auto">
				<source src="<?php echo "{$media_dir}$filename.mp4"; ?>" type="video/mp4" label="SD">
				<source src="<?php echo "{$media_dir}hd-$filename.mp4"; ?>" type="video/mp4" label="HD">
				<track kind="captions" src="<?php echo "{$media_dir}caption/$filename.vtt"; ?>" srclang="en" label="English" default>
				<!-- FALLBACK MESSAGE -->
				<p class="bg-error">Sorry! Your browser is not supported or javascript is not available!</p>
			</video>
		</div>
		<div class="title-container padding">
			<h3><?php echo $title; ?>  <small><?php echo $artist; ?></small></h3>
		</div>
		<div class="share-container padding"><!-- Only Show after video play complete! --></div>
		<div class="description-container padding">
			<strong>Published on <?php echo date('M j, Y', strtotime($timestamp)); ?></strong><br />
			<span class="text-justify" id="description-content"><?php echo $description; ?></span>
		</div>
		
		<div class="comment-container padding">
			
			<div class="comment-form">
				<div class="comment-success">
					<p class="bg-success">Thank you for posting a comment!</p>
				</div>
				<form id="comment-form" method="POST">
					<div class="form-group">
						<textarea class="form-control" maxlength="1024" style="resize:none" name="comment" id="comment-textarea" placeholder="Enter Comment"></textarea>
						<div class="row">
							<div class="col-xs-3 col-xs-push-9">
									<input type="button" class="btn btn-primary btn-sm" value="Comment" id="button-comment" />
									<input type="button" class="btn btn-default btn-sm" value="Cancel" id="button-cancel" />
							</div>
						</div>
					</div>
				</form>
				<hr />
			</div>
			<h4>COMMENTS</h4>
			<div class="comment-content">
			
			</div>
			
		</div>
	</div>
	<?php endif; ?>
	
	
	<!-- RIGHT PANE -->
	<div class="side-bar padding">
	<p><strong>Related Videos</strong></p>
	
	<?php 
	$myCon = new mysqli("localhost", "root", "", "ea_data");
	$ctr = 0;
	if($myCon->connect_error) { die("Connection failed: " . $myCon->connect_error); }
	$query = "SELECT * FROM video limit 20";
	$result = $myCon->query($query);
	
	if($result->num_rows > 0) {
		while($output = $result->fetch_assoc()) {
			$checkfile = glob("../media/tmp/tmp_{$output['file']}.*");
			if(empty($checkfile)) {
				$str = <<<EOT
		<div class="side-bar-content">
			<a href="watch?v={$output['guid']}" title="{$output['title']}">
			<img aria-hidden="true" alt="" src="../media/img/{$output['file']}.jpg" width="168" height="94" />
			<div class="side-bar-text">
				<strong>{$output['title']}</strong>
				<p>{$output['artist']}</p>
			</div>
			</a>
		</div>
EOT;
				if($output['guid']!=$_GET['v']) {
					echo $str;
				}
				
			}
			
		}
	}
	$myCon->close();
	?>
	
	</div>
	<!-- END OF RIGHT PANE -->
</div>

<!-- END OF PAGE CONTENT -->



<div class="my-footer text-center">
	<a class="up-arrow" href="#top" data-toggle="tooltip" title="TO TOP">
	    <span class="glyphicon glyphicon-chevron-up"></span>
	</a><br />
	<p>EA Local &copy; 2017 <small>Scripting and design by EA</small></p>
</div>

<!-- Scripts here for fast load time -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/video.js"></script>
<script src="js/video-reso.js"></script>
<script>
console.log("<?php echo $user_level; ?>");
console.log("<?php echo $caption; ?>");
console.log("Cleaned GUID: <?php echo htmlspecialchars($_GET['v']); ?>");
console.log("Found File: <?php echo $title; ?>");
console.log("Interesting Error: <?php echo $error; ?>");

// CHANGE LOG: REMOVED JWPLAYER
var guid = "<?php echo htmlspecialchars($_GET['v']); ?>";
videojs('player', {
	controls: true,
	autoplay: true,
	aspectRatio: '16:9',
});
videojs('player').videoJsResolutionSwitcher();

$(document).ready(function() {
	var radio;

	$('body').contextmenu(function() {
		return false;
	});
	//Login Status:
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
	<?php endif;?>
	//End of Login
	$(".comment-success").hide();
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
	$(".radio-popup").click(function() {
		player.pause(true);
		if(radio && !radio.closed) {
			radio.focus();
		} else {
			radio = window.open("audioplayer","Music Player","status=0,resizable=0,scrollbars=0,width=350,height=410");
			radio.focus();
		}
	});
	$("footer a[href='#top']").on('click', function(e) {
		if(this.hash !== "") {
			e.preventDefault();
			var hash = this.hash;

			$('html,body').animate({
				scrollTop: $(hash).offset().top
			}, 900, function() {
				window.location.hash = hash;
			});
		}
	});
	$(".share-container").hide();
	$("#button-comment, #button-cancel").hide();
	$("#comment-form").focusin(function() {
		$("#button-comment, #button-cancel").show();
	});
	$("#comment-form").blur(function() {
		$("#button-comment, #button-cancel").hide();
	});
	loadCommentSection();

	// 25bbdcd06c32d477f7fa1c3e4a91b032
	$("#download").click(function() {
		<?php if($video_ready==0): ?>
		$("#modal-error").modal("show");
		<?php else: ?>
		$("#download-page").attr("src","download.php?n=25bbdcd06c32d477f7fa1c3e4a91b032&o=0001");
		<?php endif; ?>
		console.log("clicked-me");
		return false;
	});
	
	$("#description-content").each(function() {
		var content = $(this).html();
		if(content.length > 120) {
			var lessText = content.substr(0, 120);
			var moreText = content.substr(120, content.length - 120);
			var formattedText = lessText + "<span class='more-content'>" + moreText + "</span><div class='row'>&nbsp;</div><div class='row'><div class='col-xs-4 col-xs-offset-5'><button class='btn btn-link btn-xs read-more'>Show More</button></div></div>";
            $(this).html(formattedText);
            $(".more-content").hide();
		}
	});
	$(".read-more").click(function() {
		if($(".more-content").is(":visible")) {
			$(".more-content").fadeOut(300, function() {
				$(".read-more").html("SHOW MORE");
			});
		} else {
			$(".more-content").fadeIn(600, function() {
				$(".read-more").html("SHOW LESS");
			});
			
		}
		e.preventDefault();
	});
	$("#button-cancel").click(function() {
		$("#comment-textarea").val("");
		$("#button-comment, #button-cancel").hide();
	});
	$("#button-comment").click(function() {
		var comment = $("#comment-textarea").val();
		$("#comment-form").fadeOut(200);
		$.post("postcomment.php",
				{comment: comment, guid: guid}
		).done(function() {
			$(".comment-success").fadeIn(500);
			$("#button-comment, #button-cancel").hide();
			loadCommentSection();
		});
	});
});


function loadCommentSection() {
	$(".comment-content").fadeOut(200, function() {
		$(".comment-content").empty();
		$.ajax({
			url: "comment-feed.php?guid="+guid
		}).done(function(data) {
			$(".comment-content").html(data);
			$(".comment-content").fadeIn(300);
		});
	});
	
}

</script>
</body>
</html>