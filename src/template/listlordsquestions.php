<?php
	if(isset($_GET["date"])){
		$date = $_GET["date"];
		
		// If it's monday, change date format
		if(date('D',strtotime($date))== "Mon") {
			$LordsMonday = date("dmY",strtotime($date));
		} else {
		// If it's not a monday, find the date of the previous monday
			$LordsMonday = date("dmY",strtotime('previous monday',strtotime($date)));
		}
	} else {
		$date = date('Y-m-d');
	}
	
	// For Lords we have to load the questions for that monday
	if(!isset($LordsMonday)){
		$LordsMonday = date('dmY',strtotime("previous monday"));
	}

	// Load URL and pull the divs "questionpanel"
	$LordsUrl = "http://www.lordswhips.org.uk/oral-questions/".$LordsMonday;
	$content = file_get_contents($LordsUrl);
	// The date of the questions is always wrapped in the below class
	$SplitOutDays = explode( '<p class="txt666 txtbold">' , $content );
	// Remove the waffle at the beginning of the page
	$SplitOutDays = array_slice($SplitOutDays,1);
	// For each date element, remove the question text
	for($i=0; $i<count($SplitOutDays); $i++) {
		$SplitOutDays[$i] = explode('<div class="clr15"></div>',$SplitOutDays[$i]);
		$SplitOutDays[$i] = array_shift($SplitOutDays[$i]);
		$SplitOutDays[$i] = trim(str_replace('</p>',"",$SplitOutDays[$i]));
		//$SplitOutDays[$i] = strtotime($SplitOutDays[$i]);
	}
	
	// Now load the question text
	$SplitOutQuestions = explode( '<div class="questionpanel">' , $content );
	// Remove all the waffle at the beginning of the page
	$SplitOutQuestions = array_slice($SplitOutQuestions,1);
	// Go through each question and pull out only the good stuff
	for($i=0; $i<count($SplitOutQuestions); $i++) {
		$SplitOutQuestions[$i] = str_replace("<br />","<p>",$SplitOutQuestions[$i]);	
		$SplitOutQuestions[$i] = str_replace("</style>","<p>",$SplitOutQuestions[$i]);
		$SplitOutQuestions[$i] = explode("<p>",$SplitOutQuestions[$i]);
			for($j=0; $j<count($SplitOutQuestions[$i]); $j++){
				$SplitOutQuestions[$i][$j] = explode(" to ask ", $SplitOutQuestions[$i][$j]);
				$SplitOutQuestions[$i][$j] = trim(array_shift($SplitOutQuestions[$i][$j]));
			}
	$SplitOutQuestions[$i] = array_filter($SplitOutQuestions[$i]);
	}
	
	for($i=0; $i<count($SplitOutQuestions); $i++) {
		$LordsQuestions[$i] = array("date" => date("Y-m-d",strtotime($SplitOutDays[$i])),
									"questions" => array_values($SplitOutQuestions[$i]));
	}
	// print_r($SplitOutDays);
	// print_r($SplitOutQuestions);
	// print_r($LordsQuestions);

	// Load XML file containing all current MP's data 
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7CIsEligible=true/") or die("Can't load Peers");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);
	
	// Array with party ID and party color
	require_once('colors.php');	
	
	// Find which question array is the current date
	if(isset($LordsQuestions)) {
		for($i=0; $i<count($LordsQuestions); $i++) {
			if($date == $LordsQuestions[$i]['date']){
				$chosendate = $i;
			}
		}
	}
	if(!isset($chosendate)){
		echo '<a class="list-group-item">
			 <h4 class ="list-group-item-heading">No Lords questions on '.$date.'</h4></a>';
	} else {
	
		for($i=0; $i<count($LordsQuestions[$chosendate]['questions']); $i++) {
			$CurrentQuestioner = trim($LordsQuestions[$chosendate]['questions'][$i]);	
			// Let's now check each Lord to find which Lord asked the question 
			for ($y=0; $y < $memberscount; $y++){
				$CurrentLord = trim($qxml->Member[$y]->DisplayAs);
				if($CurrentQuestioner == $CurrentLord) { 
					$DodsId=$qxml->Member[$y]->attributes()->Dods_Id;
					$MemberId=$qxml->Member[$y]->attributes()->Member_Id;
					$DisplayAs=$qxml->Member[$y]->DisplayAs;
					$party=$qxml->Member[$y]->Party;
					$PartyID =$qxml->Member[$y]->Party[0]->attributes()->Id;              	          	          	     
					$color = $colors[intval($PartyID)];
				
				   // Now build an array with all the information we want	
				   $qarray[] = array( 'DisplayAs'=>$DisplayAs,
									  'DodsId'=>$DodsId,
									  'MemberId'=>intval($MemberId),
									  'party'=>$party,
									  'color'=>$color
									);
				}	
			}	
		}
					
		$hint = "";	
	
		// Generate the list of questions 	
		for($i=0; $i < count($qarray); $i++) {			
			$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$qarray[$i]["DodsId"].'.jpg.jpg';
			$hint=$hint .'<a id="q" class="list-group-item" onclick="loadlords(\''.$qarray[$i]["MemberId"].'\');return false;" href="#">
			   <img src="'.$imageurl.'" class="img-rounded mini-member-image pull-left">
			   <h4 class="list-group-item-heading"><span class="partybox" style="background:'.$qarray[$i]["color"].'"></span>'.$qarray[$i]["DisplayAs"].'</h4>
			   <p class="list-group-item-text">'.$qarray[$i]["party"].'</p></a>';
		}
	
		echo $hint;
	}
	
?>