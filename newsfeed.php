<?php
/*
 * Feed from BBC World News: URL: http://feeds.bbci.co.uk/news/world/rss.xml
 */
if(isset($_POST['page'])) {
	$pageNum = $_POST['page'];
} else {
	$pageNum = 1;
}

echo '<h3>World News by BBC World <small>powered by EA</small></h3><hr>';

// Variables
$xmlLoc = "http://feeds.bbci.co.uk/news/world/rss.xml";
$xmlData = file_get_contents($xmlLoc);
$namespace = "http://search.yahoo.com/mrss/";
$channel = new SimpleXMLElement($xmlData);
$item = $channel->channel->item;
$page_count = $item->count();
$per_page = $pageNum * 5;
$ctr = $pageNum * 5 - 5;
include('createPaginator.php');
// TODO FIX LOOP FOR BBC FEED

for($x=intval($ctr); $x < intval($per_page); $x++) {
	
	$title = $item[$x]->title;
	$description = $item[$x]->description;
	$link = $item[$x]->link;
	$pubDate = $item[$x]->pubDate;
	$media = $item[$x]->children($namespace)->thumbnail[0]->attributes();
	$mediaURL = $media['url'];

	echo "<div class='post-content media'>";
	echo "<div class='media-left'>";
	echo "<img src='" . $mediaURL . "' class='media-object' id='image-content'>";
	echo "</div>";
	echo "<div class='media-body'>";
	echo "<h4 class='media-heading'><a href='" . $link . "'>" . $title . "</a></h4>";
	echo "<p><small>Published " . getInterval($pubDate) . ", " . getDateLocal($pubDate) . "</small></p>";
	echo "<p class='text-primary'>" . $description . "</p>";
	echo "</div>";
	echo "<hr /></div>";

}

function getDateLocal($data) {
	date_default_timezone_set('Asia/Manila');
	$date = strtotime($data);
	$date = date("D M d Y, g:i:sA", $date);
	return $date;
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

echo setPaginator($pageNum, ceil($page_count / 5));

?>