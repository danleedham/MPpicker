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
	include('qs-lordsgetwhips.php');
	
	// Load XML file containing all current Lords
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7CIsEligible=true/") or die("Can't load Peers");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);
	
	// Array with party ID and party color
	require_once('core/colors.php');	
	
	// If beta images are loaded prior to this then skip
	if(!isset($feed)){
		$feed = file_get_contents("betaimages.xml");
		$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
		$imagescount = count($betaimages);
	}
	
	
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
			    
			    $imageurl = "";
                for($ii=0; $ii < $imagescount; $ii++) {
                    if (intval($betaimages->member[$ii]->memberid) == $MemberId){
                        $BetaId = $betaimages->member[$ii]->imageid;
                        $imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
                    }
                }
                // If no new portrait, pull from MemberPhoto
                if ($imageurl == ""){
                    $imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
                }
			   // Now build an array with all the information we want	
			   $qarray[] = array( 'DisplayAs'=>$DisplayAs,
								  'DodsId'=>$DodsId,
								  'MemberId'=>intval($MemberId),
								  'party'=>$party,
								  'color'=>$color,
								  'imageurl'=>$imageurl
								);
			}	
		}	
		$hint = "";	
	}	
	// Generate the list of questions 	
	for($i=0; $i < count($qarray); $i++) {			
		$hint=$hint .'<a id="m'.$qarray[$i]["MemberId"].'" class="list-group-item list-section-list" onclick="qsloadlords(\''.$qarray[$i]["MemberId"].'\');return false;" href="#">
		   <img src="'.$qarray[$i]["imageurl"].'" class="mini-member-image pull-left">
		   <h4 class="list-group-item-heading"><span class="partybox" style="background:'.$qarray[$i]["color"].' !important"></span>'.$qarray[$i]["DisplayAs"].'</h4>
		   <p class="list-group-item-text">'.$qarray[$i]["party"].'</p>
		   </a>';
	} 
	echo $hint; 
	
	?>
