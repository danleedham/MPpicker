<?php

	if(!isset($id) && isset($_GET["id"])){
		$id=$_GET["id"];
		if($id !== "countspeakers") {
			$id = "currentlivequestion";
		}
	} else {
		$id = "currentlivequestion";
	}
	
	// date should be passed in the URL, but if not...
	if(isset($_GET["date"])){
		$date = $_GET["date"];
	} else {
		if(!isset($date)) {
			$date = date('Y-m-d');
		}
	}
	
	// Get the events list for today
	$dontecho = true;
	include 'wind-events.php';
	
	// If Events are found, return the House of Commons
	if(isset($Events)){
		for($i=0; $i<count($Events); $i++){
			if($Events[$i]['name'] == "House of Commons"){
				$event = $Events[$i]['id'];
			}
		}
		include 'wind-getclips.php';	
	}	
		//echo $event;
	
	$QuestionLogs = array();
	for($i=0; $i<count($Events); $i++){
		if(preg_match("/(\(\d+\))/",$Events[$i]['name'])){
			$QuestionLogs[] = array( 'name' => $Events[$i]['name'],
									 'time' => $Events[$i]['time']);
		}
	}
	// Make sure we're only pulling the latest time. This is to fix the flip flop of the logs
	usort($QuestionLogs, function($a, $b) {
		return strcmp($a["time"], $b["time"]);
	});
	
	// Get the last question
	$CurrentQuestionTitle = $QuestionLogs[count($QuestionLogs)-1]['name'];
	
	// Extract the UIN
	preg_match("/(\(\d+\))/",$CurrentQuestionTitle,$matches);
	
	// Remove the ()s
	$CurrentQuestion = intval(str_replace(array('(',')'),'',$matches[0]));
	
	echo '<input type="number" id="'.$id.'" value="'.$CurrentQuestion.'">';
?>