<?php 

?>

<!DOCTYPE html>
<html>
<body>

<form action="upload1.php" method="post" enctype="multipart/form-data">
    Select video to upload:
    <input type="file" name="vidfile" id="vidfile"><br />
    Select caption to upload:
    <input type="file" name="captionfile" id="captionfile"><br />
    <input type="submit" value="Upload Content" name="submit">
</form>

<div id="radio">
</div>

<script src="../test/js/jquery.js"></script>
<script src="../test/js/player/jwplayer.js"></script>
<script>
jwplayer.key="5QW+dzm1wQvn5HOGmh5cF4j22BtPf5y0199vuQ==";

jwplayer('radio').setup({
	file: 'http://icecast.eradioportal.com:8000/yesfm_manila',
	type: 'audio/mpeg',
	height: 40,
	width: 250
});
</script>
</body>
</html>