<?php 	
// Section should be the in time for for the section
if(!isset($event) && isset($_GET["event"])){
	$event = $_GET["event"];
}
if(!isset($section) && isset($_GET["section"])){
	$section = $_GET["section"];
}

// First get the arrays of clips and events
include 'wind-getclips.php';
	
	
// Find which section we want to get. Should match 'time' element
if(isset($section)) {
	for($i=0; $i<count($Events); $i++) {
		if($section == $Events[$i]['time']) {
			$Sectioni= $i;
		}
	}
}

// Remove all clips that aren't after the time set
$NewClips = array();
 
for ($i=0; $i<count($Clips); $i++) {
	$EventTime = strtotime($Events[$Sectioni]['time']);
	$ClipTime = strtotime($Clips[$i]['time']);
	if($ClipTime >= $EventTime) {
		$NewClips[] = $Clips[$i];
	}
}

$Clips = $NewClips;

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

// Now let's go through each clip and extract the helpful bits of information
for($i=0; $i<(count($Clips)); $i++) {	
	
	// Get the MP's Full Title
	$ClipMP = explode('MP', $Clips[$i]['name']);
	$ClipMP = array_shift($ClipMP);
	$ClipMP = $ClipMP.'MP';
	$ClipMP = trim($ClipMP);
	
	// Let's now check each MP to find which MP asked the question 
	for ($y = 0; $y < $memberscount; $y++){	
		$CurrentMP = trim($qxml->Member[$y]->FullTitle);
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
   $wraparray[] = array('member'=>$MemberId,
					  	'DisplayAs'=>$DisplayAs,
					  	'DodsId'=>$DodsId,
					  	'MemberId'=>intval($MemberId),
					  	'constituency'=>$Constituency,
					  	'party'=>$party,
					  	'color'=>$color
				);

}

	if(isset($keepdupes) && $keepdupes !== "keep") {
	// Now rebuild the array and only keep a member if they've not spoken yet...
	$newwraparray = array();
	for ($i=0; $i < count($wraparray); $i++) {
		$alreadyin = false;
		foreach ($newwraparray as $key => $value) {
			if(intval($wraparray[$i]['member']) == intval($value['member'])) {
				$alreadyin = true;
				break;
			}
		}
		if(!isset($alreadyin) or !$alreadyin == true) {
			$newwraparray[] = ($wraparray[$i]);
		}
	}
	$wraparray = $newwraparray;
}
$hint = "";	
if(isset($wraparray)){
	$newlength = count($wraparray);
	// Generate the list of questions 	
	for($i=0; $i < $newlength; $i++) {
		for($ii=0; $ii < $imagescount; $ii++) {
			if (trim($betaimages->member[$ii]->KnownAs) == $wraparray[$i]["DisplayAs"]){				
			$BetaId = $betaimages->member[$ii]->imageid;
			}
		}
		if (!isset($BetaId) or $BetaId == ""){
			$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$wraparray[$i]["DodsId"].'.jpg.jpg';
		} else {
			$imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg'; 
		}
	
		$hint=$hint .'<a id="m'.$wraparray[$i]["MemberId"].'" class="list-group-item" onclick="load('.$wraparray[$i]["MemberId"].') ;return false;"  href="#">
		   <img src="'.$imageurl.'" class="img-rounded mini-member-image pull-left">
		   <h4 class="list-group-item-heading"><span class="partybox" style="background:'.$wraparray[$i]["color"].'"></span>'. $wraparray[$i]["DisplayAs"].'</h4>
		   <p class="list-group-item-text">'.$wraparray[$i]["constituency"].' ('.$wraparray[$i]["party"].')</p></a>';
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
