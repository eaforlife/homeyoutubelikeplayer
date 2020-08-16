<?php

if(isset($_POST['page'])) {
	$pageNum = $_POST['page'];
} else {
	$pageNum = 1;
}

echo '<h3>Front Page of Reddit <small>powered by EA</small></h3><hr>';

function getURL($data) {
	$link = '[link]';
	$newLink = strstr($data,$link,true);
	preg_match('/<span><a href="(.+)">/', $newLink, $output_array);
	//preg_match("/&lt;span&gt;&lt;a href=&quot;(.+)&quot;&gt;/", $newLink, $output_array);
	return $output_array[1];
		
}

function hasImage($data) {
	if(!preg_match('/<img src="(.+)" alt/', $data)) {
		return false;
	} else {
		return true;
	}
}

function getImage($data) {
	preg_match('/<img src="(.+)" alt/', $data, $output_array);
	return $output_array[1];
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

//Variables
$reddit = simplexml_load_file("https://www.reddit.com/.rss");
$entry = $reddit->entry;
$page_count = $reddit->entry->count();
$per_page = $page_count / 5;
include('createPaginator.php');

// Parse Content to Page
for($x=($pageNum * $per_page - $per_page); $x < ($pageNum * $per_page); $x++) {
	$title = $entry[$x]->title;
	$titlelink = $entry[$x]->link['href'];
	$content = $entry[$x]->content;
	$posted = $entry[$x]->updated;
	$author = $entry[$x]->author->name;
	$authorurl = $entry[$x]->author->uri;
	$redditlink = $entry[$x]->category['label'];
	$externallink = getURL($content);
	$thumbnail = "";

	if (hasImage($content) == true) {
		$thumbnail = getImage($content);
	} else {
		$thumbnail = "";
	}
	//Timezone
	echo "<div class='post-content media'>";
	if(empty($thumbnail)) {
		echo "<div class='media-left'>";
		echo "<img src='../media/img/reddit-logo.png' class='media-object' id='image-content'>";
		echo "</div>";

	} else {
		echo "<div class='post-content media'>";
		echo "<div class='media-left'>";
		echo "<img src='" . $thumbnail . "' class='media-object' style='width:200px'>";
		echo "</div>";
	}
	echo "<div class='media-body'>";
	echo "<h4 class='media-heading'><a href='" . $externallink . "'>" . $title . "</a></h4>";
	echo "<p class='text-primary'><a href='" . $titlelink . "'>Comments</a></p>";
	echo "<p><small>Post updated " . getInterval($posted) . " on: " .  getDateLocal($posted) . " by <a href='" . $authorurl . "'>" . $author . "</a>";
	echo " on <a href='https://www.reddit.com/" . $redditlink . "'>" . $redditlink . "</a></small></p>";
	echo "</div>";
	echo "<hr /></div>";

}

// Set Paginator
// TODO: Paginator fix next prev
echo setPaginator($pageNum, $per_page);

?>