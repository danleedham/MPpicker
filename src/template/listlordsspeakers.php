<?php
	if(isset($_GET["date"])){
		$date = $_GET["date"];
	} else {
		$date = date('Y-m-d');
	}
	
	if(isset($_GET["chosenBusiness"])){
		$chosenBusiness = $_GET["chosenBusiness"];
	}

	//$LordsUrl = "http://www.lordswhips.org.uk/todays-lists/";
	// Development URL:
	$LordsUrl = "http://leedhammedia.com/todays-lists/";
	$content = file_get_contents($LordsUrl);
	
	// The list of speakers starts with the list
	$SplitOutSpeakers = explode( '<em>Speakers</em>:' , $content );
	// Remove the waffle at the beginning of the page
	$SplitOutSpeakers = array_slice($SplitOutSpeakers,1);
	// Remove the waffle at the end of the page
	$KeepGoodStuff = explode('<em>Notes:</em>',$SplitOutSpeakers[count($SplitOutSpeakers)-1]);
	$SplitOutSpeakers[count($SplitOutSpeakers)-1] = $KeepGoodStuff[0];
	
	$NewSpeakers = array();
	// For each set of speakers clean up
	for($i=0; $i<count($SplitOutSpeakers); $i++) {
		$SplitOutSpeakers[$i] = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;","",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;","",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('&nbsp;&nbsp;',"",$SplitOutSpeakers[$i]);		
		$SplitOutSpeakers[$i] = str_replace('style="margin-left:70.9pt">',">",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('<p style="margin-left:70.9pt;">','',$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('style="margin-left:36.0pt">',">",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('<p>&nbsp;</p>',"",$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('<em>(Maiden speech)</em>','',$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = preg_replace('/[0-9]+/', '', $SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('L ','L&nbsp;',$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = str_replace('&nbsp;','. ',$SplitOutSpeakers[$i]);
		$SplitOutSpeakers[$i] = explode('</p>',$SplitOutSpeakers[$i]);
		for ($j=0; $j<count($SplitOutSpeakers[$i]); $j++) {
			$SplitOutSpeakers[$i][$j] = str_replace('<p>','',$SplitOutSpeakers[$i][$j]);
			$SplitOutSpeakers[$i][$j] = str_replace("\n",'', $SplitOutSpeakers[$i][$j]);
			$SplitOutSpeakers[$i][$j] = explode('. ',$SplitOutSpeakers[$i][$j]);
			if(count($SplitOutSpeakers[$i][$j]) < 2 ) { 
			} else {
				$SplitOutSpeakers[$i][$j] = trim($SplitOutSpeakers[$i][$j][1]).', '.trim($SplitOutSpeakers[$i][$j][0]).'.';
			}	
			if(!is_array($SplitOutSpeakers[$i][$j])) {
				if(!strpos($SplitOutSpeakers[$i][$j],'<em>')) {
					$NewSpeakers[$i][$j] = $SplitOutSpeakers[$i][$j];
				}
			}
		}
	}
	
	print_r($NewSpeakers);
	// Reindex 
	for($i=0; $i<count($NewSpeakers); $i++) {
		$NewSpeakers[$i] = array_values($NewSpeakers[$i]);
	}			
		
	$LordsQuestions = $NewSpeakers;	
	
	if(!isset($chosenBusiness)){
		$chosenBusiness = 0;
	}
	
	// Load XML file containing all current Lords's data 
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7CIsEligible=true/") or die("Can't load Peers");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);
	
	// Array with party ID and party color
	require_once('colors.php');	
	
	for($i=0; $i<count($LordsQuestions[$chosenBusiness]); $i++) {
		$CurrentSpeaker = trim($LordsQuestions[$chosenBusiness][$i]);
		// Let's now check each Lord to find which Lord asked the question 
		for ($y=0; $y < $memberscount; $y++){
			$CurrentLord = trim($qxml->Member[$y]->ListAs);
			if($CurrentSpeaker == $CurrentLord) { 
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
	}
	echo $hint;
?>