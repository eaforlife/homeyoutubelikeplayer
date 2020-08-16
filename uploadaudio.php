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
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal" enctype="multipart/form-data" id="myForm">
	<fieldset <?php if(!empty($_POST)) { echo "disabled"; } ?>>
		<label class="control-label">Enter New Audio</label>
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
		<label for="audiofile" class="col-md-2">Audio File:</label>
		<div class="col-md-10">
			<input id="audiofile" type="file" class="form-control" name="audiofile" >
		</div>
	</div>
	<div class="form-group">
		<label for="imgfile" class="col-md-2">Thumbnail File:</label>
		<div class="col-md-10">
			<input id="imgfile" type="file" class="form-control" name="imgfile">
			<span class="help-block">Note: Only JPEG, PNG and GIF are supported.</span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			<button type="submit" class="btn btn-default" id="submit">Submit</button>
		</div>
	</div>
	</fieldset>
</form>
<?php endif; ?>

<?php 

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
	$ffmpegCommand = "C:\wamp64\www\media\convert.bat audio {$data_name} {$data_ext}";
	pclose(popen("start /B " . $ffmpegCommand, "r"));
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = $artist = $description = "";
	$result = "";
	$upload_ok = 1;
	
	if(is_dir("../media/audio/tmp/")) {
		$uploaddir = "../media/audio/tmp/";
	} else {
		mkdir("../media/audio/tmp/" , 0777);
		$uploaddir = "../media/audio/tmp/";
	}

	$audiofile = $uploaddir . basename($_FILES['audiofile']['name']);
	$imgfile = $uploaddir . basename($_FILES['imgfile']['name']);
	$audioType = pathinfo($audiofile,PATHINFO_EXTENSION);
	$imgType = pathinfo($imgfile,PATHINFO_EXTENSION);
	$fileName = "0001";

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
	if($imgType != 'jpg' && $imgType != 'jpeg' && $imgType != 'png' && $imgType != 'gif') {
		$upload_ok = 0;
		$result .= "Unsupported thumbnail file<br />";
	}
	if($audioType != 'mp3' && $audioType != 'm4a' && $audioType != 'ogg' && $audioType != 'wav') {
		$upload_ok = 0;
		$result .= "Unsupported audio file<br />";
	}
	$result .= "Initializing...<br />";

	$ctr = 0;
	do {
		$ctr++;
		$fileName = sprintf("%04s", $ctr);
	} while (file_exists('../media/audio/' . $fileName . '.mp3'));
	$result .= "File name created: $fileName <br/>";

	$tmpAudio = $uploaddir . "tmp_" . $fileName . "." . $audioType;

	if($upload_ok == 1) {
		if (move_uploaded_file($_FILES['audiofile']['tmp_name'], $tmpAudio)) {
			$result .= "Setting temporary container: Saved to: $tmpAudio<br />";
			if (move_uploaded_file($_FILES['imgfile']['tmp_name'], "../media/audio/img/" . $fileName . "." . $imgType)) {
				$result .= "Processing files..<br />";
				$upload_ok = 1;
			} else {
				$upload_ok = 0;
				$result .= "Possible file upload attack1!\n";
			}
		} else {
			$upload_ok = 0;
			$result .= "Possible file upload attack!\n";
		}

	} else {
		$result .= "Error Found. Upload aborted. <br />";
	}
		
	$result .= "<br />";
	$result .= "<br />Errors: <br />";
	if($upload_ok == 1) {
		convertFile($fileName, $audioType);
		$result .= "Process complete..<br />";
		$result .= "Cleaning up..<br />";
		//unlink($tmpAudio);
		//unlink("../media/audio/tmp/log.txt");
		$result .= "Successfully uploaded file<br />";
			
		$result .= "Saving metadata...<br />";
		$conn = new mysqli("localhost", "root", "", "ea_data");
		if($conn->error) die("Connection to database failed: " . $conn->error);
		$nGuid = $ntitle = $ndesc = $nfile = $nimg = "";
		$query = $conn->prepare("INSERT INTO audio (guid, title,description, file, img) VALUES (?, ?, ?, ?, ?)");
		$query->bind_param("sssss", $nGuid, $ntitle, $ndesc, $nfile, $nimg);
			
		$nGuid = md5($fileName, false);
		$ntitle = $title . " - " . $artist;
		$ndesc = $description;
		$nfile = "../media/audio/" . $fileName . ".mp3";
		$nimg = "../media/audio/img/" . $fileName . "." . $imgType;
		$query->execute();
			
		$query->close();
		$conn->close();
		$result .= "Process Complete.";
		$result .= "No errors.";
	} else {
		$result .= $result;
	}
	$result .= "<br />***** END OF REPORT ******<br />";
	
	$_SESSION['result'] = $result;
	if($upload_ok == 0) {
		header("Location: admin1?s=fail");
	} else {
		header("Location: admin1?s=success");
	}
}
?>


