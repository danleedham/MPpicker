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

if(!isset($count) && isset($_GET['count'])){
	$count=$_GET["count"];
} else {
	$count = "1";
}
	
$xmlDoc=new DOMDocument();
$xmlDoc->load('http://lda.data.parliament.uk/tvclips.xml?_view=basic&member=http://data.parliament.uk/members/'.$m.'&_properties=thumbnailUrl&_sort=-startDate&_pageSize='.$count);
$x=$xmlDoc->getElementsByTagName('thumbnailUrl');

$screenshotoutput = array();

// by default we'd expect two back because we asked for two!
$screenshotcount = $x->length;	
if ($screenshotcount == 0) {
} else {	
	for($i=0; $i<($x->length); $i++) {
			$thumbnailUrl = $x->item($i)->getAttribute('href');
			$thumbnailUrl = str_replace("width=480","width=960",$thumbnailUrl);
			$screenshotarray[] = array('url' => $thumbnailUrl);	
	}
	
	sort($screenshotarray);	
	foreach ($screenshotarray as $key => $value) {
		$screenshotoutput[]= ($value["url"]);
	}
}

?> 
