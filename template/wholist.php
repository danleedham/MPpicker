<?php
$xmlDoc=new DOMDocument();
	//get parameters from URL
	$house=$_GET["house"];
		if ($house !== "") { 
			$houseurl = '|'."House=".$house; 
		}
		if (!$house) { 
			$houseurl = ""; 
		}
		if ($house == "both") { 
			$houseurl = ""; 
		}
			
	$gender=$_GET["sex"];
		if ($gender !== "") { 
			$genderurl = '|'."gender=".$gender; 
		}
		if ($gender === "all") { 
			$genderurl = ""; 
		}
		if (!$gender) { 
			$genderurl = ""; 
		}
	
	$party=$_GET["party"];
		if ($party !== "") { 
			$partyurl = '|'."party=".$party; 
		}
		if (!$party) { 
			$partyurl = ""; 
		}
	
	// Append search URL based on what positions the people may have. Append search query plus return data
	$positions=$_GET["position"];
		if (!$positions) { 
			$positionsurl = "";
			$returnpositions = "";  
		} elseif ($positions == "cabinet") { 
			$positionsurl = '|'."holdscabinetpost=true";
			$returnpositions = "GovernmentPosts".'|';
		} elseif ($positions == "shadow") { 
			$positionsurl = '|'."holdsshadowcabinetpost=true";
			$returnpositions = "OppositionPosts".'|'; 
		} elseif ($positions == "government") { 
			$positionsurl = '|'."holdsgovernmentpost=true"; 
			$returnpositions = "GovernmentPosts".'|';
		} elseif ($positions == "opposition") { 
			$positionsurl = '|'."holdsoppositionpost=true";
			$returnpositions = "OppositionPosts".'|';  
		} elseif ($positions == "parliamentary") { 
			$positionsurl = '|'."holdsparliamentarypost=true";
			$returnpositions = "ParliamentaryPosts".'|';
		} elseif ($positions == "none") { 
			$positionsurl = '|'."holdsoppositionpost=false|holdsgovernmentpost=false";
			$returnpositions = ""; 
		} elseif ($positions == "all") {
			$positionsurl = ""; 
			$returnpositions = "GovernmentPosts|OppositionPosts|";
		}
	
	// Committees are easy as we can search by them
	$committee=$_GET["committee"];
		if ($committee !== "") { 
			$committeeurl = '|'."committee=".$committee;
		}
		if (!$committee or $committee == "") { 
			$committeeurl = ""; 
		} else { 
			$returncommittee = "Committees".'|'; 
		}
	
	// Departments don't allow direct querying, instead by positions attached to departments
	$department=$_GET["department"];
		if ($department) { 
			if ($positions == "cabinet" or $positions == "government") {
				$url = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Government/Current/';
			} elseif ($positions == "shadow" or $positions == "opposition") {
				$url = $url2 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Opposition/Current/';
			} else {
				$url1 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Government/Current/';
				$url2 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Opposition/Current/';
				$doc1 = new DOMDocument();
				$doc1->load($url1);

				$doc2 = new DOMDocument();
				$doc2->load($url2);
				   
				// get 'res' element of document 1
				$res1 = $doc1->getElementsByTagName('Department')->item(0);

				// iterate over 'item' elements of document 2
				$items2 = $doc2->getElementsByTagName('Post');
				for ($i = 0; $i < $items2->length; $i ++) {
					$item2 = $items2->item($i);

					// import/copy item from document 2 to document 1
					$item1 = $doc1->importNode($item2, true);

					// append imported item to document 1 'res' element
					$res1->appendChild($item1);
				}	
				$xmlDoc=$doc1;
			} 
		}
		if ($department == "") { 
			$departmenturl = "";
		}
		
	// Which sort function to use prior to rendering
	$sortby=$_GET["sortby"];

	$photos=$_GET["photos"];
	
	$joined=$_GET["joined"];
		if (!$joined) {
			$joindeurl = "";
		} else {
			$joinedurl = '|'."joinedsince=".$joined;
		}
	
	// Now load things
	if ($house !== "" ) {
		if(!$url){
			$url = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true".$houseurl.$partyurl.$genderurl.$positionsurl.$committeeurl.$joinedurl."/".$returnpositions.$returncommittee."BiographyEntries";
		}
	echo '<!-- '.$url.'-->';
	if(!$department){$xmlDoc->load($url);}
		$x=$xmlDoc->getElementsByTagName('Member');
	$colors = array (
								"0"	  =>   "#000000",
								"4"	  =>   "#0087DC",
								"7"   =>   "#D46A4C",
								"8"   =>   "#000000",
								"15"  =>   "#DC241f",
								"17"  =>   "#FAA61A",
								"22"  =>   "#008142",
								"29"  =>   "#FFFF00",
								"30"  =>   "#008800",
								"31"  =>   "#99FF66",
								"35"  =>   "#70147A",
								"38"  =>   "#9999FF",
								"44"  =>   "#6AB023",
								"47"  =>   "#FFFFFF");
	$hint = "";
	
	//Load xml with codes for new Parliament Beta images
	$feed = file_get_contents("../betaimages.xml");
	$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
	$imagescount =  count($betaimages);
		
	// Let's build an array with our results in
	for($i=0; $i<($x->length); $i++) {
		
		$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
		$DisplayAs=$x->item($i)->getElementsByTagName('DisplayAs');
		if($department){
			$HansardName = $x->item($i)->getElementsByTagName('HansardName')[0]->textContent;
		}
		$House=$x->item($i)->getElementsByTagName('House');
		$ListAs=$x->item($i)->getElementsByTagName('ListAs');
		$StartDate=$x->item($i)->getElementsByTagName('HouseStartDate');
		$DateOfBirth=$x->item($i)->getElementsByTagName('DateOfBirth');
		$Party=$x->item($i)->getElementsByTagName('Party');
			foreach ($Party as $PartiesForId){ 
					$PartyID = $PartiesForId->getAttribute('Id'); 
			}
		$MemberId=$x->item($i)->getAttribute('Member_Id');
		$DodsId=$x->item($i)->getAttribute('Dods_Id');
		
		if ($photos !== "screenshot") {				
			if ($House[0]->textContent == "Commons") {
				if ($photos !== "stock") {
					$imageurl = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/".intval($MemberId);
				} else {
					for($ii=0; $ii < $imagescount; $ii++) {
						if (trim($betaimages->member[$ii]->KnownAs) == trim($DisplayAs[0]->textContent)){
							$BetaId = $betaimages->member[$ii]->imageid;
						}
					}
					$imageurl = 'https://api20170418155059.azure-api.net/photo/'.$BetaId.'.jpeg?crop=CU_1:1&width=240&quality=60';
					if (@getimagesize($imageurl)){
					} else {
						$imageurl = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/".intval($MemberId);
					}
				}	
			} else { 
					$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
			}
		} else {
			$imageurl = 'images/'.$DodsId.'.jpg';
		}											
		// Fix for the Lords not having decent photos... yet!
		$Const=$x->item($i)->getElementsByTagName('MemberFrom');
		$GovernmentPosts=$x->item($i)->getElementsByTagName('GovernmentPosts');
		$OppositionPosts=$x->item($i)->getElementsByTagName('OppositionPosts');
		$ParliamentaryPosts=$x->item($i)->getElementsByTagName('ParliamentaryPosts');
		$Committees=$x->item($i)->getElementsByTagName('Committee');
		
		$GovernmentPostsList = array();
		for($ii = 0; $ii < $GovernmentPosts->length; $ii ++) {
			if (!strtotime($GovernmentPosts->item($ii)->getElementsByTagName('EndDate')[0]->textContent) >= time()) {
				if($GovernmentPosts->item($ii)->getElementsByTagName('HansardName')[0]->textContent){
					$GovernmentPostsList[] = $GovernmentPosts->item($ii)->getElementsByTagName('HansardName')[0]->textContent;
				} else {
					$GovernmentPostsList[] = $GovernmentPosts->item($ii)->getElementsByTagName('Name')[0]->textContent;
				} 
			}
		}
		$OppositionPostsList = array(); 			
		for($ii = 0; $ii < $OppositionPosts->length; $ii ++) {
			if (!strtotime($OppositionPosts->item($ii)->getElementsByTagName('EndDate')[0]->textContent) >= time()) {
				if($OppositionPosts->item($ii)->getElementsByTagName('HansardName')[0]->textContent){
					$OppositionPostsList[] = $OppositionPosts->item($ii)->getElementsByTagName('HansardName')[0]->textContent;
				} else {
					$OppositionPostsList[] = $OppositionPosts->item($ii)->getElementsByTagName('Name')[0]->textContent;
				}
			}
		}
		$ParliamentaryPostsList = array();
		for($ii = 0; $ii < $ParliamentaryPosts->length; $ii ++) {
			if (!strtotime($ParliamentaryPosts->item($ii)->getElementsByTagName('EndDate')[0]->textContent) >= time()) {
				if($ParliamentaryPosts->item($ii)->getElementsByTagName('HansardName')[0]->textContent){
					$ParliamentaryPostsList[] = $ParliamentaryPosts->item($ii)->getElementsByTagName('HansardName')[0]->textContent; 
				} else {
					$ParliamentaryPostsList[] = $ParliamentaryPosts->item($ii)->getElementsByTagName('Name')[0]->textContent;
				}
			}
		}
		$CommitteesList = array();
		for($ii = 0; $ii < $Committees->length; $ii ++) {
			if (!strtotime($Committees->item($ii)->getElementsByTagName('EndDate')[0]->textContent) >= time()) {
			
				$CommitteesList[] = $Committees->item($ii)->getElementsByTagName('Name')[0]->textContent; 
			}
		}
	
		$whoarray[] = array(  'FullTitle'=>$FullTitle[0]->textContent,
							  'DisplayAs'=>$DisplayAs[0]->textContent,
							  'ListAs'=>$ListAs[0]->textContent,
							  'House'=>$House[0]->textContent,
							  'StartDate'=>$StartDate[0]->textContent,
							  'DateOfBirth'=>$DateOfBirth[0]->textContent,
							  'Party'=>$Party[0]->textContent,
							  'PartyId'=>intval($PartyID),
							  'color'=>$colors[intval($PartyID)],
							  'DodsId'=>intval($DodsId),
							  'MemberId'=>intval($MemberId),
							  'imgid' => $imgid,
							  'HansardName'=>$HansardName,
							  'constituency'=>$Const[0]->textContent,
							  'GovenmentPosts'=>$GovernmentPostsList,
							  'OppositionPosts'=>$OppositionPostsList,
							  'ParliamentaryPosts'=>$ParliamentaryPostsList,
							  'Committees'=>$CommitteesList,
							  'imageurl'=>$imageurl,
							  );
	}
			
	// Count how many questions there are
	$length = count($whoarray);	
	
	// If there are questions, sort the questions & generate list
	if ($length !== 0) {
		
		//Let's sort the data if requested			
		if($sortby == "first"){
				sort($whoarray);
		}
		if ($sortby == "last") {
			usort($whoarray, function($a, $b) {
				return strcmp($a["ListAs"], $b["ListAs"]);
			});
		} 
		if ($sortby == "joinedlast") {
			usort($whoarray, function($a, $b) {
				return strcmp($b["StartDate"], $a["StartDate"]);
			});
		}
		if ($sortby == "joinedfirst") {
			usort($whoarray, function($a, $b) {
				return strcmp($a["StartDate"], $b["StartDate"]);
			});
		}
		if ($sortby == "oldest") {
			usort($whoarray, function($a, $b) {
				return strcmp($a["DateOfBirth"], $b["DateOfBirth"]);
			});
		}	
		if ($sortby == "youngest") {
			usort($whoarray, function($a, $b) {
				return strcmp($b["DateOfBirth"], $a["DateOfBirth"]);
			});
		}
		if ($sortby == "consta") {
			usort($whoarray, function($a, $b) {
				return strcmp($a["constituency"], $b["constituency"]);
			});
		}	
		if ($sortby == "constz") {
			usort($whoarray, function($a, $b) {
				return strcmp($b["constituency"], $a["constituency"]);
			});
		}			

		// Generate the list of questions 	
		for($j=0; $j < $length; $j++) {	
		
			if ($house == trim($whoarray[$j]["House"]) or $house == "both") {
				$Govecho = "";
				if($whoarray[$j]["GovenmentPosts"]){
					foreach($whoarray[$j]["GovenmentPosts"] as $value) {
					   $Govecho=$Govecho.'<h4 class="list-group-item-heading post">'. $value.'</h4>';
					}
				}
				$Oppecho = "";
				if($whoarray[$j]["OppositionPosts"]){
					foreach($whoarray[$j]["OppositionPosts"] as $value) {
					   $Oppecho=$Oppecho.'<h4 class="list-group-item-heading post">'. $value.'</h4>';
					}
				}
				$Parecho = "";
				if($whoarray[$j]["ParliamentaryPosts"]){
					foreach($whoarray[$j]["ParliamentaryPosts"] as $value) {
					   $Parecho=$Parecho.'<h4 class="list-group-item-heading post">'. $value.'</h4>';
					}
				}
				$Comecho = "";
				if($whoarray[$j]["Committees"]){
					foreach($whoarray[$j]["Committees"] as $value) {
					   $Comecho=$Comecho.'<h4 class="list-group-item-heading post">'. $value.'</h4>';
					}
				}
			
				if($Govecho or $Oppecho or $Parecho or $Comecho) {
					$positionecho = '<div class="list-group-item">'.$Govecho.$Oppecho.$Parecho.$Comecho.' 
									</div>';
				} else {
					$positionecho = "";
				}
				if($department){
					$positionecho = '<div class="list-group-item"><h4 class="list-group-item-heading post">'.$whoarray[$j]["HansardName"].'</h4> 
									</div>';
				}
				
				if($photos == "screenshot"){
					$ifscreenshot = " screenshot";
				}
				
				$hint=$hint .'		
					<div class="bootcards-cards bootcards-group">
						<div id="contactCard" class="guesswho">
							<div class="panel panel-default">
								<div class="panel-heading clearfix">
									<h3 class="panel-title pull-left">'.$whoarray[$j]["DisplayAs"].'</h3>
								</div>
								<div class="list-group">
									<div class="list-group-item">
										<img src="'.$whoarray[$j]["imageurl"].'" class="img-rounded group-member-image'.$ifscreenshot.'">
									</div>
									<div class="list-group-item" style="background-color:'.$whoarray[$j]["color"].'" id="constituency">
										<h4 class="list-group-item-heading">'.$whoarray[$j]["Party"].'</h4>
									</div>
									<div class="list-group-item">
										<h4 class="list-group-item-heading">'.$whoarray[$j]["constituency"].'</h4>
									</div>
									'.$positionecho.'
								</div>
							</div>
						</div><!--end contact card-->
					</div>';
			}
		}
	}

	// If there's nothing to echo, request the user tries again  
	if ($hint=="") {
		$response='<div class="col-sm-12 bootcards-cards bootcards-group">
					   <div id="contactCard">
						  <div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left">No Members found, please try again</h3>
							</div>
						  </div>
						</div>
					  </div>';
	} else {
		$response=$hint;
	}		
		//output the response
	echo $response;
}

?>