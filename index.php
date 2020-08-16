<!DOCTYPE html>
<?php 
/*
 * TODO LIST:
 * !Important: Create Modal
 * 1. [DONE]Attempt AJAX XML for RSS (load different rss source on single page on demand).
 * 2. [IN-PROGRESS]Design for Video Player integration.
 * 3. Add functionality on Admin Page
 */
session_start();
$guid = $filename = $title = $artist = $description = $caption = $timestamp = "";
$error = "";
$media_dir = "../media/";

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

?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<link href="../test/css/bootstrap.min.css" rel="stylesheet">
	<link href="../test/css/font-awesome.min.css" rel="stylesheet">
	<style>
	html {
		height: 100%;
	}
	
	body {
		min-height: 700px;
		background: #E5E8E8 no-repeat;
		padding-top: 60px;
	}
	
	#content {
		background: #F8F9F9 no-repeat;
		min-height: 400px;
		padding: 20px;
	}
	
	.footer {
		position: relative;
		height: 60px;
		width: 100%;
		bottom: 0;
		background: #566573;
		padding-top: 15px;
	}
	
	.loadBar {
		position:absolute;
		width: 100%;
		height: 100%;
		top:0;
		left:0;
		background: rgba(0, 0, 0, 0.56);
		z-index:999;
		display:none;
	}
	
	/* Links */
	#ea-playlist a, #ea-playlist a:link, #ea-playlist a:visited {
		color: #17202A;
		text-decoration: none;
	}
	#ea-playlist a:hover, #ea-playlist a:active {
		color: #34495E; 
		text-decoration: none;
	}
	/* Loading Bar */
	#loadingBar {
			padding-top: 40px;
	}
	#loadingBar .progress {
			width: 80%;
			margin: 0 auto;
	}
	
	/* Carousel */
	.carousel {
	  height: 500px;
	}
	.carousel .item {
	  height: 500px;
	  background-color: #777;
	}
	.carousel-inner > .item > img {
	  position: absolute;
	  top: 0;
	  left: 0;
	  min-width: 100%;
	  height: 500px;
	}
	.post-content {
		margin-bottom: 10px;
	}
	#content-feed #image-content {
			width: 300px;
	}
	
	/* Interactive CSS */
	@media screen and (max-width: 480px) {
		.carousel, .carousel .item, .carousel-inner > .item > img {
			height: 250px;
		}
		#content-feed #image-content {
			width: 180px;
		}
		#content {
			height: 100%;
		}
	}
	</style>
</head>

<body>
	<!-- NavBar here -->

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
	        <li id="home-nav"><a href="./?p=home">Home</a></li>
	        <li id="reddit-nav"><a href="./?p=reddit">Reddit</a></li>
	        <li id="news-nav"><a href="./?p=news">News</a></li>
	      </ul>
	      <span class="navbar-text" id="dt"></span>
	     
			<ul class="nav navbar-nav navbar-right">
			<li><a class="radio-popup" title="Radio"><span class="glyphicon glyphicon-music" aria-hidden="true"></span></a></li>
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
	
	<!-- Banner -->
<!-- LETS HIDE BANNER
	<div id="banner" class="carousel slide" data-ride="carousel" data-interval="10000" data-pause="false" data-wrap="true">
      <++ Indicators ++>
      <ol class="carousel-indicators hidden-xs hidden-sm hidden-md hidden-lg">
        <li data-target="#banner" data-slide-to="0" class="active"></li>
        <li data-target="#banner" data-slide-to="1"></li>
        <li data-target="#banner" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="../media/img/banner.jpg" alt="First slide">
        </div>
        <div class="item">
          <img class="second-slide" src="../media/img/banner1.jpg" alt="Second slide">
        </div>
        <div class="item">
          <img class="third-slide" src="../media/img/banner2.jpg" alt="Third slide">
        </div>
      </div>
      
    </div>
/-->
    <!--  Banner End -->
	
	<div class="container" id="content">
		<div class="row"><!--  DELETED SIDEBAR -->
			<div class="col-lg-12" id="postBar">
				<div class="text-center text-info" id="loadingBar">
					<h2>Loading Content</h2>
					<p>Your content will be ready shortly.</p>
					<div class="progress">
					  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" style="width:100%">
					    <span>Scanning content..</span>
					  </div>
					</div>
				</div>
				<div id="content-feed">
				<!-- Call from AJAX -->
				</div>
			</div>
		</div>
	</div>
	<!--  Load Bar -->
	<!--  TODO: WORK ON LOADING BAR -->
	<!--
	<div class="modal fade" id="loadingBar" role="dialog">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-body text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>
			</div>
		</div>
	</div>
	-->
	
	
<footer class="text-center" style="margin-top: 90px">
	<a class="up-arrow" href="#top" data-toggle="tooltip" title="TO TOP">
	    <span class="glyphicon glyphicon-chevron-up"></span>
	</a><br />
	<p>EA Local &copy; 2017 <small>Scripting and design by EA</small></p>
</footer>
	
	<!-- Javascript Codes -->
	<script src="../test/js/jquery.js"></script>
	<script src="../test/js/bootstrap.min.js"></script>
	<script>
		var radio;
		$(document).ready(function() {
			var loadedDoc = "<?php 
				if(isset($_GET['p'])) {
					if($_GET['p'] == 'reddit' || $_GET['p'] == 'home' || $_GET['p'] == 'news')	{
						echo $_GET['p'];
					}
				}
			?>";
			//setInterval(getcurrenttime, 1000);
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
			
			
			console.log(loadedDoc);
			$("#loadingBar").hide();
			if(loadedDoc === '' || loadedDoc === 'home') {
				$("#loadingBar").show();
				$("#content-feed").load('eafeed.php', function() {
					$("#loadingBar").fadeOut(500);
					$("div#ea-content").hide().each(function(i) {
						  $(this).delay(i*100).fadeIn(1000);
					});
					
				});
				$("#home-nav").addClass("active");
			}
			if(loadedDoc === 'reddit') {
				$("#loadingBar").show();
				$("#content-feed").load('redditfeed.php', function() {
					$("#loadingBar").fadeOut(500);
					$("div.post-content").hide().each(function(i) {
						  $(this).delay(i*100).fadeIn(1000);
					});
				});
				$("#reddit-nav").addClass("active");
			}
			if(loadedDoc === 'news') {
				$("#loadingBar").show();
				$("#content-feed").load('newsfeed.php', function() {
					$("#loadingBar").fadeOut(300);
					$("div.post-content").hide().each(function(i) {
						  $(this).delay(i*100).fadeIn(1000);
					});
				});
				$("#news-nav").addClass("active");
			}
			$("#content-feed").on('click', '.pagination a', function (e) {
				e.preventDefault();
				$("#loadingBar").fadeIn(100);
				$("#content-feed").empty();
				var pageNum = $(this).attr("data-page");
				if(loadedDoc === 'reddit') { // if reddit is clicked
					$("#content-feed").load('redditfeed.php',{"page":pageNum}, function () {
						$("#loadingBar").fadeOut(300);
						$('html,body').animate({
							scrollTop: $(hash).offset().top
						}, 900, function() {
							window.location.hash = hash;
						});
					});
				}
				if(loadedDoc === 'news') { // if reddit is clicked
					$("#content-feed").load('newsfeed.php',{"page":pageNum}, function () {
						$("#loadingBar").fadeOut(500);
						$('html,body').animate({
							scrollTop: $(hash).offset().top
						}, 900, function() {
							window.location.hash = hash;
						});
					});
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
			$(".radio-popup").click(function() {
				if(radio && !radio.closed) {
					radio.focus();
				} else {
					radio = window.open("audioplayer","Music Player","status=0,resizable=0,scrollbars=0,width=350,height=410");
				}
			});


			
		});

		function getcurrenttime() {
			var dt = new Date();
			var date = dt.toDateString();
			var hour = dt.getHours();
			var min = dt.getMinutes();
			var sec = dt.getSeconds();
			var ampm = 'AM';
			var convertedHour = hour;
			var newMin;

			if(hour > 12 && hour <= 24) {
				ampm = 'PM';
				hour = hour - 12;
			}
			if(min < 10 && min >= 0) {
				min = '0' + min;
			}
			if(sec < 10 && sec >= 0) {
				sec = '0' + sec;
			}

			document.getElementById("dt").innerHTML = date + "  " + hour + ":" + min + ":" + sec + " " + ampm;
		}
	</script>
</body>
</html>