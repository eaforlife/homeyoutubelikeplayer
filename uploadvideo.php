<?php session_start(); 
$user_level = ""; // 1-Admin, 2-User, 3-Anon
$user_name = "";
if(isset($_SESSION['user_name'])) {
	$user_name = $_SESSION['user_name'];
	$user_level = $_SESSION['user_level'];
} else {
	$user_level = 3;
	$user_name = "";
}
?>

<?php if(empty($_POST)): ?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal" enctype="multipart/form-data">
	<fieldset <?php if(!empty($_POST)) { echo "disabled"; } ?>>
		<label class="control-label">Enter New Video</label>
		<div class="form-group">
			<label for="title" class="col-md-2">Title:</label>
			<div class="col-md-10">
				<input id="title" type="text" class="form-control" name="title" placeholder="Song Title">
			</div>
		</div>
		<div class="form-group">
			<label for="artist" class="col-md-2">Artist:</label>
			<div class="col-md-10">
				<input id="artist" type="text" class="form-control" name="artist" placeholder="Artist(s) Name">
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-md-2">Description:</label>
			<div class="col-md-10">
				<textarea style="resize:none;" class="form-control" rows="3" id="description" name="description" maxlength="3064"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="vidfile" class="col-md-2">Video File:</label>
			<div class="col-md-10">
				<input id="vidfile" type="file" class="form-control" name="vidfile" >
				<span class="help-block">Supported format: Any popular video containers</span>
			</div>
		</div>
		<div class="form-group">
			<label for="captionfile" class="col-md-2">Caption File:</label>
			<div class="col-md-10">
				<input id="captionfile" type="file" class="form-control" name="captionfile">
				<span class="help-block">Supported format: WebVTT or SRT</span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-11">
				<button type="submit" class="btn btn-default">Submit</button>
			</div>
		</div>
	</fieldset>
</form>
<?php else: ?>

<?php 
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = $artist = $description = "";
	$result = "";
	$upload_ok = 1;
	if(is_dir("../media/tmp/")) {
		$uploaddir = "../media/tmp/";
	} else {
		mkdir("../media/tmp/" , 0777);
		$uploaddir = "../media/tmp/";
	}

	$videoFile = $uploaddir . basename($_FILES['vidfile']['name']);
	$captionFile = $uploaddir . basename($_FILES['captionfile']['name']);
	$videoType = pathinfo($videoFile,PATHINFO_EXTENSION);
	$captionType = pathinfo($captionFile,PATHINFO_EXTENSION);
	$fileName = "0001";
	$caption = "";

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

	function convertFile($data_name, $data_ext) {
		$ffmpegCommand = "C:\wamp64\www\media\convert.bat video {$data_name} {$data_ext}";
		pclose(popen("start /B ".$ffmpegCommand, 'r'));
	}


	/*Validate Data*/
	if(empty($_POST["title"])) {
		$upload_ok = 0;
		$result .= "Title Field is invalid<br />";
	} else {
		$title = trimData($_POST["title"]);
	}
	if(empty($_POST["artist"])) {
		$upload_ok = 0;
		$result .= "Artist Field is invalid<br />";
	} else {
		$artist = trimData($_POST["artist"]);
	}
	if(empty($_POST["description"])) {
		$upload_ok = 0;
		$result .= "Description Field is invalid<br />";
	} else {
		$description = trimDesc($_POST["description"]);
	}
	if(isset($_FILES['captionfile']) && $_FILES['captionfile']['error']==0) {
		if($captionType != 'vtt' && $captionType != 'srt') {
			$upload_ok = 0;
			$result .= "Unsupported Caption file<br />";
		} else {
			$caption = "true";
		}
	} else {
		$caption = "false";
	}
	if($videoType != 'mp4' && $videoType != 'avi' && $videoType != 'webm' && $videoType != 'mkv') {
		$upload_ok = 0;
		$result .= "Unsupported Video file<br />";
	}
	$result .= "Validation complete<br />";
	$ctr = 0;
	do {
		$ctr++;
		$fileName = sprintf("%04s", $ctr);
	} while (file_exists('../media/' . $fileName . '.mp4'));
	$result .= "File name created: $fileName <br/>";

	$tmpVideo = $uploaddir . "tmp_" . $fileName . "." . $videoType;

	if($upload_ok == 1) {
		if(isset($_FILES['captionfile']) && $_FILES['captionfile']['error']==0) {
			if(!move_uploaded_file($_FILES['captionfile']['tmp_name'], "../media/caption/" . $fileName . "." . $captionType)) {
				$upload_ok = 0;
				$result .= "Unable to process caption file!\n";
			} 
		}
		if (move_uploaded_file($_FILES['vidfile']['tmp_name'], $tmpVideo)) {
			$result .= "Intializing file...<br />";
		} else {
			$upload_ok = 0;
			$result .= "Unable to process file!\n";
		}
	} else {
		$result .= "Error Found. Upload aborted. <br />";
	}
	
	if($upload_ok == 1) {
		$result .= "Setting temporary container: Saved to: $tmpVideo<br />";
		$result .= "Processing files..<br />";
		convertFile($fileName, $videoType);
		$result .= "Process complete..<br />";
		$result .= "Cleaning up..<br />";
		$result .= "Successfully uploaded file<br />";
		
		$result .= "Saving metadata...<br />";
		$conn = new mysqli("localhost", "root", "", "ea_data");
		if($conn->error) die("Connection to database failed: " . $conn->error);
		$nGuid = $ntitle = $nartist = $ndesc = $nfile = $ncap = "";
		$query = $conn->prepare("INSERT INTO video (guid,title,artist,description,file,caption) VALUES (?, ?, ?, ?, ?, ?)");
		$query->bind_param("ssssss", $nGuid, $ntitle, $nartist, $ndesc, $nfile, $ncap);
		
		$nGuid = md5($fileName, false);
		$ntitle = $title;
		$nartist = $artist;
		$ndesc = $description;
		$nfile = $fileName;
		$ncap = $caption;
		$query->execute();
		
		$query->close();
		$conn->close();
		$result .= "Process Complete.";
		$result .= "No errors.";
	} else {
		$result .= $result;
	}
	$result .= "<br />***** END OF REPORT ******<br />";
	$_SESSION['user_name'] = $user_name;
	$_SESSION['user_level'] = $user_level;
	$_SESSION['result'] = $result;
	if($upload_ok == 0) {
		header("Location: admin1.php?s=fail");
	} else {
		header("Location: watch?v=$nGuid");
	}
}

?>

<?php endif; ?>