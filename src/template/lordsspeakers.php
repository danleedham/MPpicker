<?php 
	// Expect integer value which will be the array nth value which picks which speakers list
	if(isset($_GET["chosenBusiness"])){
		$chosenBusiness = $_GET["chosenBusiness"];
	}
	if(!isset($chosenBusiness)){
		$chosenBusiness = 0;
	}
	
	// Go scrape Lords Whips. Expect 2D array of $LordsSpeakers. 
	// No inputs required as it's only ever today's list
	include('listlordsspeakers-whips.php');
	
	// Load XML file containing all current Lords
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7CIsEligible=true/") or die("Can't load Peers");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);
	
	// Array with party ID and party color
	require_once('colors.php');	

	for($i=0; $i<count($LordsSpeakers[$chosenBusiness]); $i++) {
		$CurrentSpeaker = trim($LordsSpeakers[$chosenBusiness][$i]);
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
	}	
	// Generate the list of questions 	
	for($i=0; $i < count($qarray); $i++) {			
		$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$qarray[$i]["DodsId"].'.jpg.jpg';
		$hint=$hint .'<a id="q" class="list-group-item" onclick="loadlords(\''.$qarray[$i]["MemberId"].'\');return false;" href="#">
		   <img src="'.$imageurl.'" class="img-rounded mini-member-image pull-left">
		   <h4 class="list-group-item-heading"><span class="partybox" style="background:'.$qarray[$i]["color"].'"></span>'.$qarray[$i]["DisplayAs"].'</h4>
		   <p class="list-group-item-text">'.$qarray[$i]["party"].'</p></a>';
	} 
	echo $hint; 
	
	?>