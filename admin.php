<!DOCTYPE html>

<html>
<head><title>Admin Page</title>

	<link href="../test/css/bootstrap.min.css" rel="stylesheet">
	<style>
	body {
		padding-top:50px;
		padding-bottom:20px;
	}
	</style>

</head>
<body>
	<!-- Navigation Header -->
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Menu</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          	</button>
				<a class="navbar-brand" href="#" title="EA Local Home">EA Admin Page</a>
			</div>
			<div class="navbar-collapse collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#" title="Home Page">Home</a></li>
					<li><a href="http://www.youtube.com" title="Want to watch videos over the internet? Visit youtube!">Youtube</a></li>
					<li><a href="http://www.viu.com" title="Watch officially subbed Korean and Japanese shows over the internet only on VIU!">VIU</a></li>
					<li><a href="http://www.iFlix.com" title="Watch on-demand shows anytime over the internet only on iFlix!">iFlix</a></li>
				</ul>
				<form class="navbar-form navbar-right">
					  <div class="input-group">
					    <input type="text" class="form-control" placeholder="Search">
					    <div class="input-group-btn">
					      <button class="btn btn-default" type="submit">
					        <i class="glyphicon glyphicon-search"></i>
					      </button>
					    </div>
					  </div>
				</form>
			</div>
		</div>
	</nav>
	
	
	<div class="container">
		<div class="col-md-12"><div class="row">&nbsp;<!-- Space Break --></div></div>
		
		<div class="col-md-8">
			<div class="row">
			<!--  Page Content Here -->
				<?php include('uploadaudio.php');
				//$xml = simplexml_load_file("../test/mediainfo.xml");
				//printf("<p>" . $xml->Music[2]->Description . "</p>");
				?>
			
			</div>
		</div>
	</div>


<!-- Javascripts here for fast load -->
	<script src="../test/js/jquery.js"></script>
	<script src="../test/js/bootstrap.min.js"></script>
</body>
</html>