<?php
$myCon = new mysqli("localhost", "root", "", "ea_data");
$items = array();

if($myCon->connect_error) {
	die("Connection failed: " . $myCon->connect_error);
}
$query = "SELECT * FROM video";
$result = $myCon->query($query);

if($result->num_rows > 0) {
	while($output = $result->fetch_assoc()) {
		$items[] = $output;
	}
}
$myCon->close();
shuffle($items);
?>
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-xs-8 col-xs-offset-2">
			<div class="input-group">
				<input id="search" type="text" class="form-control" name="search" placeholder="Search...">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button">
					<span class="glyphicon glyphicon-search"></span>
					</button>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row">&nbsp;</div>
	<div class="row"><div class="col-xs-12">&nbsp;</div></div>
	
	<div class="row justify-content-start" id="ea-playlist">
<?php 
		for($x=0;$x<count($items);$x++) {
			$checkfile = glob("../media/tmp/tmp_{$items[$x]['file']}.*");
			if(empty($checkfile)) {
				$str = <<<EOT
		<div class="col-md-4">
			<div id="ea-content" style="width: 285px; height: 230px; margin: 0 auto; padding: 5px">
				<a href="watch?v={$items[$x]['guid']}">
				<img class="thumbnail" src="../media/img/{$items[$x]['file']}.jpg" alt="{$items[$x]['title']}" width="285" height="160" />
				<h4>{$items[$x]['title']} <small>{$items[$x]['artist']}</small></h4>
				</a>
			</div>
		</div>
EOT;
				echo $str . "\n";
			}
		}
		echo "<div class='clearfix'></div>\n";
?>
	</div>
	
	