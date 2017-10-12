<?php include 'wind-getclips.php';
	
	if(isset($Events)){
		foreach ($Events as $key => $value) {
			if ($value["time"]){
			echo '<option value="'. $value["time"].'">'. $value["name"].'</option>';
			$hasevents = true;
		   }
		}
	}	
	
	if (!isset($event)) { 
		echo '<option value="">Please Select Event First</option>';
	}
	
	
	if(!isset($hasevents) or $hasevents = false) {
		echo '<option value="">No events for this date</option>';
	}
?>
						