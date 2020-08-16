<?php
$newspath = "https://news.google.com/news?cf=all&hl=en&ned=en_ph&output=rss";
$xml = new DOMDocument();
$xml->load($newspath);

//Get News Feed
$news = $xml->getElementsByTagName("item");
for($x=0;$x<=2;$x++) {
	$news_title=$news->item($x)->getElementsByTagName("title")->item(0)->childNodes->item(0)->nodeValue;
	echo $news_title;
}
?>


	<!-- NavBar here -->
	<div class="navbar-wrapper">
		<div class="container">
			<nav class="navbar navbar-inverse navbar-fixed-top">
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
			        <li class="active"><a href="#" id="home-nav">Home</a></li>
			        <li><a href="#" id="reddit-nav">Reddit</a></li>
			        <li><a href="#" id="news-nav">News</a></li>
			      </ul>
			      <span class="navbar-text" id="dt"></span>
			    </div>
			  </div>
			</nav>
		</div>
	</div>