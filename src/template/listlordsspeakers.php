<?php
	if(isset($_GET["date"])){
		$date = $_GET["date"];
	} else {
		$date = date('Y-m-d');
	}


	// Load URL and pull the divs "questionpanel"
	//$LordsUrl = "http://www.lordswhips.org.uk/todays-lists/";
	// Development URL:
	$LordsUrl = "http://leedhammedia.com/todays-lists/";
	$content = file_get_contents($LordsUrl);
	
	// Get the 
	// The list of speakers starts with the list
	$SplitOutBusiness = explode( '<p><em>Main Business</em></p>', $content );
	// Remove the waffle at the beginning of the page
	$SplitOutBusiness = array_slice($SplitOutBusiness,1);
	$MainBusiness = explode('</p>',$SplitOutBusiness[0]);
	$MainBusiness = trim(html_entity_decode(str_replace('<p style="margin-left:36.0pt">',"",$MainBusiness[0])));
	
	print_r($MainBusiness);
	
	// The list of speakers starts with the list
	$SplitOutSpeakers = explode( '<em>Speakers</em>:' , $content );
	// Remove the waffle at the beginning of the page
	$SplitOutSpeakers = array_slice($SplitOutSpeakers,1);
	// Remove the waffle at the end of the page
	$KeepGoodStuff = explode('<em>Notes:</em>',$SplitOutSpeakers[count($SplitOutSpeakers)-1]);
	$SplitOutSpeakers[count($SplitOutSpeakers)-1] = $KeepGoodStuff[0];

	
	// For each set of speakers clean up
	for($i=0; $i<count($SplitOutSpeakers); $i++) {
		$SplitOutSpeakers[$i] = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;","",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;","",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',"",$SplitOutSpeakers[$i]);		
		$SplitOutSpeakers[$i] = str_replace(' style="margin-left:70.9pt">',">",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace(' style="margin-left:36.0pt">',">",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('<p>&nbsp;</p>',"",$SplitOutSpeakers[$i]);
	}
	//print_r($SplitOutSpeakers);

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
	
	for($i=0; $i<count($SplitOutSpeakers); $i++) {
		$LordsQuestions[$i] = array("lord" => trim($SplitOutSpeakers[$i])
									);
	}

	// Load XML file containing all current Lords's data 
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7CIsEligible=true/") or die("Can't load Peers");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);
	
	// Array with party ID and party color
	require_once('colors.php');	

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