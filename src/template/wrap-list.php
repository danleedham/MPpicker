<?php
$xmlDoc=new DOMDocument();
	
//get parameters from URL
if(!isset($house) && isset($_GET["house"])){
	$house=$_GET["house"];
}
// expecting integer
if(!isset($location) && isset($_GET["location"])){
	$location=$_GET["location"];
	$episode="http://data.parliament.uk/resources/".$location;
}
// expecting integer
if(!isset($section) && isset($_GET["section"])){
	$section=$_GET["section"];
}

// expecting keep or remove
if(!isset($keepdupes) && isset($_GET["keepdupes"])){
	$keepdupes=$_GET["keepdupes"];
} else {
	$keepdupes = "remove";
}

/* Annoyingly the agenda doesn't have the time saved alongside it
but one of the clips will have the same description
Get description of chosen section so we can find the clip number of it
$z is the name of the clip they've chosen */ 

$zmlDocStart = new DOMDocument();
$zmlDocStart->load('http://lda.data.parliament.uk/resources/'.$location.'/stackvideoitems/'.$section.'.xml?_view=basic&_properties=description');
$zStart=trim($zmlDocStart->getElementsByTagName('description')->item(0)->textContent);
$zurlStart = urlencode($zStart);

// Go get the clip that matches the question description and episode (location):
$getStarttimeurl = 'http://lda.data.parliament.uk/tvclips.xml?_view=basic&description='.$zurlStart.'&episode='.$episode.'&_properties=startDate&_sort=-startDate';
$cliStartpxml = simplexml_load_file($getStarttimeurl);
$starttime = $cliStartpxml->items->item->startDate;

$sectionEnd = strval(intval($section)+1);
$zmlDocEnd = new DOMDocument();
$DocEndURL = 'http://lda.data.parliament.uk/resources/'.$location.'/stackvideoitems/'.$sectionEnd.'.xml?_view=basic&_properties=description';


$file_headers = @get_headers($DocEndURL);
if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $exists = false;
}
else {
    $exists = true;
}
if(($exists)){
	$zmlDocEnd->load($DocEndURL);
	$zEnd=trim($zmlDocEnd->getElementsByTagName('description')->item(0)->textContent);
	$zurlEnd = urlencode($zEnd);
	// Go get the clip that matches the question description and episode (location):
	$getEndtimeurl = 'http://lda.data.parliament.uk/tvclips.xml?_view=basic&description='.$zurlEnd.'&episode='.$episode.'&_properties=startDate&_sort=-startDate';
	$clipEndxml = simplexml_load_file($getEndtimeurl);
	$endtime = $clipEndxml->items->item->startDate;
} else { 
	$endtime = "";
}

$loadclips = 'http://lda.data.parliament.uk/tvclips.xml?_properties=member&_pageSize=500&_view=basic&_view=basic&max-startDate='.$endtime.'&min-startDate='.$starttime.'&episode='.$episode;
$xmlDoc->load($loadclips);
$x=$xmlDoc->getElementsByTagName('member');

// Load XML file containing all current MP's data 
$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true/") or die("Can't load MPs");

$memberscount =  count($qxml);

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
for($i=0; $i<($x->length); $i++) {	
	
	// Some elements are subnodes and require the textContent to be extracted then trimmed
	$items = $x->item($i)->getElementsByTagName('item');
	$memberid = str_replace("http://data.parliament.uk/members/","",$items->item(0)->getattribute('href'));

	// Let's now check each MP to find which MP asked the question 
	for ($y = 0; $y < $memberscount; $y++){
		$CurrentMP = trim($qxml->Member[$y]->attributes()->Member_Id);
			if($memberid == $CurrentMP) { 
				$DodsId=$qxml->Member[$y]->attributes()->Dods_Id;
				$MemberId=$qxml->Member[$y]->attributes()->Member_Id;
				$DisplayAs=$qxml->Member[$y]->DisplayAs;
				$party=$qxml->Member[$y]->Party;
				$PartyID =$qxml->Member[$y]->Party[0]->attributes()->Id;              	          	          	     
				$color = $colors[intval($PartyID)];
				$Constituency=$qxml->Member[$y]->MemberFrom;
			}
	}
   $wraparray[] = array('member'=>$memberid,
					  	'DisplayAs'=>$DisplayAs,
					  	'DodsId'=>$DodsId,
					  	'MemberId'=>intval($MemberId),
					  	'constituency'=>$Constituency,
					  	'party'=>$party,
					  	'color'=>$color
				);

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
