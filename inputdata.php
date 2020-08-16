<?php 
$titleErr = $artistErr = $descriptionErr = $fileNameErr = "";
$title = $artist = $description = $fileName = $caption = $newHash = "";
$result = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
	$result = "Working...";
	if(empty($_POST["title"])) {
		$titleErr = "<p class='text-danger'>Required field.</p>";
	} else {
		$title = trimData($_POST["title"]);
	}
	if(empty($_POST["artist"])) {
		$artistErr = "<p class='text-danger'>Required field.</p>";
	} else {
		$artist = trimData($_POST["artist"]);
	}
	if(empty($_POST["description"])) {
		$descriptionErr = "<p class='text-danger'>Required field.</p>";
	} else {
		$description = trimDesc($_POST["description"]);
		//print_r("<pre>" . $description . "</pre>");
	}
	if(empty($_POST["fileName"])) {
		$fileNameErr = "<p class='text-danger'>Required field.</p>";
	} else {
		$fileName = trimData($_POST["fileName"]);
		$newHash = md5($fileName, false);
		if(checkHash($newHash)>=1) {
			$fileNameErr = "<p class='text-danger'>File Name already exists!.</p>";
		}
	}
	if(empty($_POST['caption'])) {
		$caption = "false";
	} else {
		$caption = "true";
	}
	
	if($fileNameErr == "" && $titleErr == "" && $artistErr == "" && $descriptionErr == "") {
		
		$doc = new DOMDocument();
		$doc->load("../test/mediainfo.xml");
		$doc->formatOutput = true;
		
		$media = $doc->documentElement;
		
		$Music = $doc->createElement("Music","");
		
		$idDom = $doc->createElement("ID",$newHash);
		$Music->appendChild($idDom);
		$titleDom = $doc->createElement("Title","");
		$titleDom1 = $doc->createCDATASection($title);
		$Music->appendChild($titleDom);
		$titleDom->appendChild($titleDom1);
		$artistDom = $doc->createElement("Artist","");
		$artistDom1 = $doc->createCDATASection($artist);
		$Music->appendChild($artistDom);
		$artistDom->appendChild($artistDom1);
		$descDom = $doc->createElement("Description","");
		$descDom1 = $doc->createCDATASection($description);
		$Music->appendChild($descDom);
		$descDom->appendChild($descDom1);
		$fileDom = $doc->createElement("FileName", $fileName);
		$Music->appendChild($fileDom);
		$capDom = $doc->createElement("Caption", $caption);
		$Music->appendChild($capDom);
		$timeDom = $doc->createElement("Timestamp", date(DATE_RSS));
		$Music->appendChild($timeDom);
		
		$media->appendChild($Music);
		$doc->save("../test/mediainfo.xml");
		
		
		$result = "<p class='bg-info'>Successfully added information. Redirecting you back to home...</p>";
		header("refresh:5;url=http://172.16.30.190/");
		
	} else {
		$result = "<p class='bg-danger'>Something went wrong while adding information.</p>";
		
	}
}

function checkHash($data) {
	$ctr = 0;
	$xml = simplexml_load_file("../test/mediainfo.xml");
	foreach($xml->Music as $music) {
		if($music->ID == $data) {
			$ctr++;
		}
	}
	return $ctr;
}

function trimDesc($data) {
	$data = nl2br($data);
	$data = trim($data);
	return $data;
}

function trimData($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
	<label class="control-label">Enter New Video</label>
	<?php echo $result; ?>
	<div class="form-group">
		<label for="title" class="col-md-2">Title:</label>
		<div class="col-md-10">
			<input id="title" type="text" class="form-control" value="<?php echo $title; ?>" name="title" placeholder="Song Title">
			<?php echo $titleErr; ?>
		</div>
	</div>
	<div class="form-group">
		<label for="artist" class="col-md-2">Artist:</label>
		<div class="col-md-10">
			<input id="artist" type="text" class="form-control" value="<?php echo $artist; ?>" name="artist" placeholder="Artist(s) Name">
			<?php echo $artistErr; ?>
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-md-2">Description:</label>
		<div class="col-md-10">
			<textarea style="resize:none;" class="form-control" rows="3" id="description" name="description" maxlength="3064"><?php echo $description; ?></textarea>
			<?php echo $descriptionErr; ?>
		</div>
	</div>
	<div class="form-group">
		<label for="fileName" class="col-md-2">File Name:</label>
		<div class="col-md-10">
			<input id="fileName" type="text" class="form-control" name="fileName" value="<?php echo $fileName; ?>" placeholder="File Name">
			<span class="help-block">Note: Do not include file path or file extension, as file IS stored in default location.</span>
			<?php echo $fileNameErr; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			<div class="checkbox">
				<label><input type="checkbox" name="caption" value="yes"> Captions</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			<button type="submit" class="btn btn-default">Submit</button>
		</div>
	</div>
</form>

<!--  /* 
	** Future use of input field marking below **
	* Empty field:
	  	<div class="input-group has-error has-feedback">
			<span class="input-group-addon">Title:</span>
			<input id="title" type="text" class="form-control" name="title" placeholder="Song Title">
			<span class="glyphicon glyphicon-remove form-control-feedback"></span>
		</div>
	* OK Field: 
		<div class="input-group">
			<span class="input-group-addon">Title:</span>
			<input id="title" type="text" class="form-control" name="title" placeholder="Song Title">
		</div>
*/ -->
