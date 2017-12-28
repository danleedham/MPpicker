<?php

// If $m isn't already set, get it from URL if it's passed
if(!isset($m) && isset($_GET['m'])) {$m=$_GET["m"];}

// If $m is not passed, set it as Theresa May (hard-coded value)
if(!isset($m)){
	$m="8";
}	
// If $house isn't already set in a require, get it from URL if it's passed
if(!isset($house) && isset($_GET['house'])){
	$house=$_GET["house"];
}

if(!isset($imagenumber) && isset($_GET['imagenumber'])){
	$imagenumber=$_GET["imagenumber"];
} else {
	$imagenumber = 0;
}

if(!isset($count) && isset($_GET['count'])){
	$count=$_GET["count"];
} elseif (isset($count)) {

} else {
	$count = strval($imagenumber+1);
}
	
$xmlDoc=new DOMDocument();
$xmlDoc->load('http://lda.data.parliament.uk/tvclips.xml?_view=basic&member=http://data.parliament.uk/members/'.$m.'&_properties=thumbnailUrl&_sort=-startDate&exists-thumbnailUrl=true&_pageSize='.$count);
$x=$xmlDoc->getElementsByTagName('thumbnailUrl');

$screenshotoutput = array();

// by default we'd expect two back because we asked for two!
$screenshotcount = $x->length;	
if ($screenshotcount == 0) {
} else {	
	for($i=0; $i<($x->length); $i++) {
			$thumbnailUrl = $x->item($i)->getAttribute('href');
			// $thumbnailUrl = str_replace("width=480","width=960",$thumbnailUrl);
			$clipid = $x->item($i)->parentNode->getAttribute('href');
			$clipref = explode("/",$clipid);
			$clipid = $clipref[4].'-'.$clipref[6];			
			$screenshotarray[] = array('url' => $thumbnailUrl,
									   'clipid' => $clipid,
									   'imagenumber' => $imagenumber);	
	}	
}

// As the main output just provide an array with the requested image
$screenshotoutput = $screenshotarray[$imagenumber];

?>
<div id="data" style="display: none;">
	<?php 
		echo $screenshotoutput['url'].','.$screenshotoutput['imagenumber'];
	?>
</div>
