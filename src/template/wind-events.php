<?php
	if(isset($_GET["date"])){
		$date = $_GET["date"];
	} else {
		$date = date('Y-m-d');
	}

	$epgURL = 'http://parliamentlive.tv/Guide/EpgDay?date='.$date.'T00%3A00%3A00%2B00%3A00';
	print_r($epgURL);
	$content = file_get_contents($epgURL);
	
	// Each events starts with a description
	$SplitOutEvents = explode( '<div class="description">', $content );
	// Remove the waffle at the beginning of the page
	$SplitOutEvents = array_slice($SplitOutEvents,1);
	
	
	$GetEventNames = $SplitOutEvents;
	for ($i=0; $i<count($SplitOutEvents); $i++){
		$GetEventNames[$i] = str_replace('<h4><a href="','',$SplitOutEvents[$i]);
		$GetEventNames[$i] = str_replace('</a></h4>',' class="event">',$GetEventNames[$i]);
		$GetEventNames[$i] = explode(' class="event">',$GetEventNames[$i]);
		$GetEventNames[$i] = trim($GetEventNames[$i][1]);

	}

	$GetEventIDs = $SplitOutEvents;
	for ($i=0; $i<count($SplitOutEvents); $i++){
		$GetEventIDs[$i] = str_replace('<h4><a href="/Event/Index/','',$SplitOutEvents[$i]);
		$GetEventIDs[$i] = explode('" class="event">',$GetEventIDs[$i]);
		$GetEventIDs[$i] = trim($GetEventIDs[$i][0]);

	}
	
	// Combine IDs and Names Please!
	$Events = array();
	for ($i=0; $i<count($SplitOutEvents); $i++){
		$Events[$i]['id'] = $GetEventIDs[$i];
		$Events[$i]['name'] = $GetEventNames[$i];
	}
	
	//print_r($Events);	

	if(isset($Events)){
		foreach ($Events as $key => $value) {
			if ($value["id"]){
			echo '<option value="'. $value["id"].'">'. $value["name"].'</option>';
			$hasevents = true;
		   }
		}
	}	

	if(!isset($hasevents)) {
		echo '<option value="">No events for this date</option>';
	}
?>
						