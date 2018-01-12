<?php 	
// Section should be the in time for for the section
if(!isset($event) && isset($_GET["event"])){
	$event = $_GET["event"];
}
if(!isset($section) && isset($_GET["section"])){
	$section = $_GET["section"];
}

// expecting keep or remove
if(!isset($keepdupes) && isset($_GET["keepdupes"])){
	$keepdupes=$_GET["keepdupes"];
} 
// expecting alpha or order
if(!isset($sort) && isset($_GET["sort"])){
	$sort=$_GET["sort"];
} 
if(!isset($Events)) {
// If we don't know where we're looking from get the arrays of clips and events
	include 'wind-getclips.php';	
}

// Find which section we want to get. Should match 'time' element
	if(isset($section) && isset($Events)) {
		for($i=0; $i<count($Events); $i++) {
			if($section == $Events[$i]['time']) {
				$Sectioni= $i;
			}
		}
	}
	if (!isset($Events)) { 
		$NoEventsSet = true;
	} else {
	
		// Remove all clips that aren't after the time set
		$NewClips = array();
 
		for ($i=0; $i<count($Clips); $i++) {
			if(!isset($section)) {
				$EventTime = strtotime($Events[$Sectioni]['time']);
			} else {
				$EventTime = strtotime($section);
			}
			$ClipTime = strtotime($Clips[$i]['time']);
			if($ClipTime >= $EventTime) {
				$NewClips[] = $Clips[$i];
			}
		}
	$Clips = $NewClips;
	}


// If there still aren't any events set hint to ""
if(isset($NoEventsSet) && $NoEventsSet == true) {
	$hint = "";
} else {
	// Load XML file containing all current MP's data 
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true/") or die("Can't load members");
	$memberscount = count($qxml);
	// Array with party ID and party color
	require_once('colors.php');	

	$hint = "";
	// If beta images are loaded prior to this then skip
	if(!isset($feed)){
		$feed = file_get_contents("betaimages.xml");
		$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
		$imagescount = count($betaimages);
	}

	// Area to remove duplicates
	if(isset($keepdupes) && $keepdupes !== "keep") {
		// Now rebuild the array and only keep a member if they've not spoken yet...
		$NewClipsArray = array();
		for ($i=0; $i < count($Clips); $i++) {
			$alreadyin = false;
			foreach ($NewClipsArray as $key => $value) {
				if($Clips[$i]['name'] == $value['name']) {
					$alreadyin = true;
					break;
				}
			}
			if(!isset($alreadyin) or !$alreadyin == true) {
				$NewClipsArray[] = ($Clips[$i]);
			}
		}
		$Clips = $NewClipsArray;
	}

	// Now let's go through each clip and extract the helpful bits of information
	for($i=0; $i<(count($Clips)); $i++) {	
		if(isset($GetLocation) && $GetLocation == "Lords") {
			$ClipMP = explode('(', $Clips[$i]['name']);
			$ClipMP = array_shift($ClipMP);
			$ClipMP = trim($ClipMP);

		} else {
			// Get the MP's Full Title
			$ClipMP = explode('MP', $Clips[$i]['name']);
			$ClipMP = array_shift($ClipMP);
			$ClipMP = $ClipMP.'MP';
			$ClipMP = trim($ClipMP);

		}	
		// Let's now check each Member to find which member asked the question (variables say MP but it can be any member)
		for ($y = 0; $y < $memberscount; $y++){	
			if($GetLocation !== "Lords"){
				$CurrentMP = trim($qxml->Member[$y]->FullTitle);
			} else {
				$CurrentMP = trim($qxml->Member[$y]->DisplayAs);
			}
				if($ClipMP == $CurrentMP) { 
					$DodsId=$qxml->Member[$y]->attributes()->Dods_Id;
					$MemberId=$qxml->Member[$y]->attributes()->Member_Id;
					$DisplayAs=$qxml->Member[$y]->DisplayAs;
					$party=$qxml->Member[$y]->Party;
					$PartyID =$qxml->Member[$y]->Party[0]->attributes()->Id;              	          	          	     
					$color = $colors[intval($PartyID)];
					$Constituency=$qxml->Member[$y]->MemberFrom;
				}
	 	}
	 	if(isset($MemberId)) {
		   $wraparray[] = array('member'=>$MemberId,
								'DisplayAs'=>$DisplayAs,
								'DodsId'=>$DodsId,
								'MemberId'=>intval($MemberId),
								'constituency'=>$Constituency,
								'party'=>$party,
								'color'=>$color
						);
		}
	}
	if (isset($sort) && $sort == "alpha") {
		if(isset($house) && $house == "Lords"){
			usort($wraparray, function($a, $b) {
				return strcmp($a["DisplayAs"], $b["DisplayAs"]);
			});
		} else {
			usort($wraparray, function($a, $b) {
				return strcmp($a["constituency"], $b["constituency"]);
			});
		}
	} 
	
	$hint = "";	
	if(isset($wraparray)){
		$newlength = count($wraparray);
		// Generate the list of questions 	
		for($i=0; $i < $newlength; $i++) {
			for($ii=0; $ii < $imagescount; $ii++) {
				if (intval($betaimages->member[$ii]->memberid) == intval($wraparray[$i]["MemberId"])){			
				    $BetaId = $betaimages->member[$ii]->imageid;
				} 
			}
			if (!isset($BetaId) or $BetaId == ""){
				$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$wraparray[$i]["DodsId"].'.jpg.jpg';
			} else {
				$imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg'; 
			}
			
			if (isset($GetLocation) && $GetLocation == "Lords") {
				$footer = $wraparray[$i]["constituency"];
				$header = $wraparray[$i]["DisplayAs"];
			} else {
				$header = $wraparray[$i]["constituency"];
				$footer = $wraparray[$i]["DisplayAs"];
			}
			$hint=$hint.'<a class="list-group-item windup-item" onclick="load('.$wraparray[$i]["MemberId"].') ;return false;"  href="#">
			   <img src="'.$imageurl.'" class="img-rounded mini-member-image pull-left">
			   <h4 class="list-group-item-heading"><span class="partybox" style="background:'.$wraparray[$i]["color"].'"></span>'. $header.'</h4>
			   <p class="list-group-item-text">'.$footer.' ('.$wraparray[$i]["party"].')</p></a>';
		}
	}
}
// Set output if no questions were found or to the correct values
if ($hint=="") {

  $response='<a class="list-group-item">
			 <h4 class ="list-group-item-heading">No members logged for the chosen criteria, sorry. </h4></a>';
} else {
	// Otherwise respond with the information required 		
			
    $response=$hint;
}	
	echo $response;
?>	   
