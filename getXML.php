<html>
<head></head>
<body>

<?php
echo("Hello world");
//$q = $_GET["q"];

$xmlDoc = new DOMDocument();
$xmlDoc->load("mediainfo.xml");

$title = $xmlDoc->getElementsByTagName('Title');

for ($x=0; $x<=$title->length-1; $x++) {
			$y=($title->item($x)->parentNode);
}

$titleTxt = ($y->childNodes);

for($ctr=0;$ctr<$titleTxt->length-1;$ctr++) {
	
		echo("Result " . $ctr . ": " . $titleTxt->item($ctr)->childNodes->item(0)->nodeValue);
	
	
}

?>

</body>
</html>

