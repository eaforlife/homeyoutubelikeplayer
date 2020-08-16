<?php
$file_dir = "../media/";

	if(isset($_GET['n']) && isset($_GET['o'])) {
		$name = htmlspecialchars($_GET['o']);
		$file = $file_dir . $name . ".mp4";
		$newfile = htmlspecialchars($_GET['n']) . ".mp4";
		
		if(file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: video/mp4');
			header('Content-Disposition: attachment; filename='.$newfile);
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
	}

?>
