<?php
	if(!isset($date) && isset($_GET["date"])){
		$date = $_GET["date"];
	} else {
		$date = date('Y-m-d');
	}
	
	// Expecting the event as string in the form of: 52c323e1-b659-40c2-b924-48b40f478313
	if(!isset($event) && isset($_GET["event"])){
		$event = $_GET["event"];
	}
	
	// Not always expecting this but when we get it it'll help us. Expecting date/time in 2017-11-14T14:51:02
	if(!isset($section) && isset($_GET["section"])){
		$section = $_GET["section"];
	}
	
	// Only go searching if we've been set an event, otherwise it's a fools errand. 
	if(isset($event) && $event !== "") {
	
		include('wind-geteventlocation.php');
		if(isset($section)) {
			$logsURL = 'http://parliamentlive.tv/Event/EventLogsBetween/'.$event.'?startTime='.$section.'Z';
		} else {
			$logsURL = 'http://parliamentlive.tv/Event/Logs/'.$event;
		}
		$content = file_get_contents($logsURL);
		// print_r($logsURL);
		$SplitOutClips = explode( '<header class="stack-item">', $content );
		$SplitOutClips = array_slice($SplitOutClips,1);	
		$GetClipTitles = $SplitOutClips;
		for ($i=0; $i<count($SplitOutClips); $i++){
			$GetClipTitles[$i] = str_replace('<h4>','',$SplitOutClips[$i]);
			$GetClipTitles[$i] = explode('</h4>',$GetClipTitles[$i]);
			$GetClipTitles[$i] = trim($GetClipTitles[$i][0]);

		}
	
		$SplitOutTimes = explode('data-time="', $content );
		$SplitOutTimes = array_slice($SplitOutTimes,1);
		$GetClipTimes = $SplitOutTimes;
		for ($i=0; $i<count($GetClipTimes); $i++){
			$GetClipTimes[$i] = explode('Z">',$GetClipTimes[$i]);
			$GetClipTimes[$i] = trim($GetClipTimes[$i][0]);

		}
		// Combine clip names and times please!
		$Clips = array();
		for ($i=0; $i<count($GetClipTimes); $i++){
			// For the Commons if the name has MP in it, presume it's a person speaking
			if(strpos($GetClipTitles[$i],"MP")){
				$Clips[$i]['time'] = $GetClipTimes[$i];
				$Clips[$i]['name'] = $GetClipTitles[$i];
				$Clips[$i]['id'] = $i;
			// For the Lords
			} elseif (isset($GetLocation) && $GetLocation == "Lords" && strpos($GetClipTitles[$i],'(')){
				// Remove titles from ministers	
				$GetClipTitles[$i] = explode(",",$GetClipTitles[$i]);
				$GetClipTitles[$i] = $GetClipTitles[$i][0];
				$Clips[$i]['time'] = $GetClipTimes[$i];
				$Clips[$i]['name'] = $GetClipTitles[$i];
				$Clips[$i]['id'] = $i;
			} elseif(strpos($GetClipTitles[$i],"Speaker")){
				// If Mr Speaker is logged
			} else {
				if($GetClipTitles[$i] !== "") {
					$Events[$i]['time'] = $GetClipTimes[$i];
					$Events[$i]['name'] = $GetClipTitles[$i];
					$Events[$i]['id'] = $i;
				}
			}
		}
	
		// Reindex arrays
		if(isset($Events) && $Events !== "") {
			$Events = array_values($Events);
			$Clips = array_values($Clips);
		}
	} else {
		$hasevents = false;
	}
	
?>		