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
	
	
	// Only go searching if we've been set an event, otherwise it's a fools errand. 
	if(isset($event)) {
		$logsURL = 'http://parliamentlive.tv/Event/Logs/'.$event;
		$content = file_get_contents($logsURL);

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
			// For the Lords if the clip name doesn't have ( presume it's a title
			} elseif (strpos($GetClipTitles[$i],"ajdlaksjdas")){
				$Clips[$i]['time'] = $GetClipTimes[$i];
				$Clips[$i]['name'] = $GetClipTitles[$i];
				$Clips[$i]['id'] = $i;
			} else {
				if($GetClipTitles[$i] !== "") {
					$Events[$i]['time'] = $GetClipTimes[$i];
					$Events[$i]['name'] = $GetClipTitles[$i];
					$Events[$i]['id'] = $i;
				}
			}
		}
	
		// Reindex arrays
		$Events = array_values($Events);
		$Clips = array_values($Clips);
	
		// print_r($Events);	

	} else {
		$hasevents = false;
	}
	
?>
						