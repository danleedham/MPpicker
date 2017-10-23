<?php
	if(isset($_GET["date"])){
		$date = $_GET["date"];
	} else {
		$date = date('Y-m-d');
	}
	
	// Expecting the event as string in the form of: 52c323e1-b659-40c2-b924-48b40f478313
	if(!isset($event) && isset($_GET["event"])){
		$event = $_GET["event"];
	}

	$epgURL = 'http://parliamentlive.tv/Guide/EpgDay?date='.$date.'T00%3A00%3A00%2B01%3A00';
	$content = file_get_contents($epgURL);
	
	// Each events starts with a description
	$SplitOutEvents = explode('href="/Event/Index/'.$event.'" class="event">', $content );
	// Remove the waffle at the beginning of the page
	$SplitOutEvents = array_slice($SplitOutEvents,1);
	
	// After this GetLocation should be either 
	$GetLocation = $SplitOutEvents;
	for ($i=0; $i<count($SplitOutEvents); $i++){
		$GetLocation[$i] = explode('<br />',$GetLocation[$i]);
		$GetLocation[$i] = trim($GetLocation[$i][1]);
		$GetLocation[$i] = explode('</p>',$GetLocation[$i]);
		$GetLocation[$i] = trim($GetLocation[$i][0]);
	}
	if(isset($GetLocation[0]) && $GetLocation[0] !== "") {
		$GetLocation = $GetLocation[0];
	}	
?>
						