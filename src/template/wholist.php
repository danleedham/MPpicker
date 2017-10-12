<?php
$xmlDoc=new DOMDocument();
	
	//get parameters from URL
	if(!isset($house) && isset($_GET["house"])){
		$house=$_GET["house"];
	}
	if (isset($house)) { 
		$houseurl = '|'."House=".$house; 
	}
	if (!isset($house)) { 
		$houseurl = ""; 
	}
	if (isset($house) && $house == "both") { 
		$houseurl = ""; 
	}

	if(isset($_GET["sex"])){
		$gender=$_GET["sex"];
	}
	if (isset($gender) and $gender !== "") { 
		$genderurl = '|'."gender=".$gender; 
	}
	if (isset($gender) && $gender == "all") { 
		$genderurl = ""; 
	}
	if (!isset($gender)) { 
		$genderurl = ""; 
	}
	
	if(isset($_GET["party"])){
		$party=$_GET["party"];
	}	
	if (isset($party) && $party !== "") { 
		$partyurl = '|'."party=".$party; 
	}
	if (!isset($party)) { 
		$partyurl = ""; 
	}
	
	// Append search URL based on what positions the people may have. Append search query plus return data
	
	if(!isset($positions) && isset($_GET["position"])){
		$positions=$_GET["position"];
	}
	else { 
		$positions = "all";
	}
		if (!isset($positions)) { 
			$positionsurl = "";
			$returnpositions = "GovernmentPosts|OppositionPosts|";  
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
	
	// Departments don't allow direct querying, instead by positions attached to departments
	if(!isset($department) && isset($_GET["department"])){
		$department=$_GET["department"];
	}
	// Annoyingly we have to load both government and opposition files apart. Then we'll add them together
	if (isset($department)) { 
		if ($positions == "cabinet" or $positions == "government") {
			$url1 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Government/Current/';
		} elseif ($positions == "shadow" or $positions == "opposition") {
			$url1 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Opposition/Current/';
		} else {
			$url1 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Government/Current/';
			$url2 = 'http://data.parliament.uk/membersdataplatform/services/mnis/Department/'.$department.'/Opposition/Current/';
		}	
		$doc1 = new DOMDocument();
		$doc1->load($url1);
	
	// If both government and opposition urls are loaded then let's add them together
		if(isset($url2)){
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
		}
		
	$xmlDoc=$doc1;	 
	
	}
	
	// Committees are easy as we can search directly by them (we only ever query by the full committee name)
	if(!isset($committee) && isset($_GET["committee"])){
		$committee=$_GET["committee"];
	}
	if (isset($committee)) { 
		$committeeurl = '|'."committee=".$committee;
	}
	if (!isset($committee) or $committee == "") { 
		$committeeurl = ""; 
		$returncommittee = "";
	} else { 
		$returncommittee = "Committees".'|'; 
	}
	
	// If they asked for committee chairs we need to return committees:	
	if (isset($positions) && $positions == "chair") {
		$returncommittee = "Committees".'|';
		$returnpositions = "";
		$positionsurl = "";
	}
	
	// If a topic has been selected we need to search by it
	if(isset($_GET["topic"])) {
		$topic=$_GET["topic"];
		$topicurl='|'.'biographyinterest='.$topic;
	} else {
		$topicurl = "";
	}
		
	// Which sort function should we use prior to rendering the list out
	if(!isset($sortby) && isset($_GET["sortby"])) {
		$sortby=$_GET["sortby"];
	}
	if(!isset($photos) && isset($_GET["photos"])) {
		$photos=$_GET["photos"];
	}
	if(!isset($joined) && isset($_GET["joined"])) {
		$joined=$_GET["joined"];
		$joinedurl = '|'."joinedsince=".$joined;
	} else {
		$joinedurl = "";
	}
	
	// Now load everything we've agreed this far
	if (isset($house) && $house !== "" ) {
		if(!isset($url)){
			$url = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true".$houseurl.$partyurl.$genderurl.$positionsurl.$committeeurl.$topicurl.$joinedurl."/".$returnpositions.$returncommittee."BiographyEntries";
		}
	echo '<!-- '.$url.'-->';
	if(!isset($department)){
		$xmlDoc->load($url);
	}
	$x=$xmlDoc->getElementsByTagName('Member');
	require_once('colors.php');
	$hint = "";
	
	//Load xml with codes for new Parliament Beta images
	$feed = file_get_contents("betaimages.xml");
	$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
	$imagescount =  count($betaimages);
		
	// Let's build an array with our results in
	for($i=0; $i<($x->length); $i++) {
		
		$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
		$DisplayAs=$x->item($i)->getElementsByTagName('DisplayAs');
		if(isset($department)){
			$HansardName = $x->item($i)->parentNode->parentNode->parentNode->getElementsByTagName('HansardName')[0]->textContent;
		} else {
			$HansardName ="";
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
		
		if (!isset($photos) or $photos !== "screenshot") {				
			if ($House[0]->textContent == "Commons") {
				if (!isset($photos) or $photos !== "stock") {
					$imageurl = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/".intval($MemberId);
				} else {
					for($ii=0; $ii < $imagescount; $ii++) {
						if (trim($betaimages->member[$ii]->KnownAs) == trim($DisplayAs[0]->textContent)){
							$BetaId = trim($betaimages->member[$ii]->imageid);
						}
					}
					$imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
					if (isset($BetaId) && $BetaId == ""){
						$imageurl = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/".intval($MemberId);
					}
				}	
			} else { 
					$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
			}
		} else {
			$imageurl = 'images/screenshot/'.$DodsId.'.jpg';
		}											
		// Fix for the Lords not having decent photos... yet!
		$Const=$x->item($i)->getElementsByTagName('MemberFrom');
		$GovernmentPosts=$x->item($i)->getElementsByTagName('GovernmentPost');
		$OppositionPosts=$x->item($i)->getElementsByTagName('OppositionPost');
		$ParliamentaryPosts=$x->item($i)->getElementsByTagName('ParliamentaryPost');
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
				if($OppositionPosts->item($ii)->getElementsByTagName('Name')[0]->textContent){
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
					$ischair = "";
				if(isset($Committees->item($ii)->getElementsByTagName('ChairDates')[0]->ChairDate->StartDate[0]->textContent)){
					$ischair = " - Chair ";
				}	
				$CommitteesList[] = $Committees->item($ii)->getElementsByTagName('Name')[0]->textContent.$ischair; 
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
							  'HansardName' => $HansardName,
							  'constituency'=>$Const[0]->textContent,
							  'GovenmentPosts'=>$GovernmentPostsList,
							  'OppositionPosts'=>$OppositionPostsList,
							  'ParliamentaryPosts'=>$ParliamentaryPostsList,
							  'Committees'=>$CommitteesList,
							  'imageurl'=>$imageurl,
							  );
	}

	// Count how many questions there are
	if(isset($whoarray)){
		$length = count($whoarray);	
	
	
		// If there are questions, sort the questions & generate list
		if ($length !== 0) {
		
			//Let's sort the data if requested			
			if(isset($sortby) && $sortby == "first"){
					sort($whoarray);
			}
			if (isset($sortby) && $sortby == "last") {
				usort($whoarray, function($a, $b) {
					return strcmp($a["ListAs"], $b["ListAs"]);
				});
			} 
			if (isset($sortby) && $sortby == "joinedlast") {
				usort($whoarray, function($a, $b) {
					return strcmp($b["StartDate"], $a["StartDate"]);
				});
				$showJoined = '';
			}
			if (isset($sortby) && $sortby == "joinedfirst") {
				usort($whoarray, function($a, $b) {
					return strcmp($a["StartDate"], $b["StartDate"]);
				});
				$showJoined = '';
			}
			if (isset($sortby) && $sortby == "oldest") {
				usort($whoarray, function($a, $b) {
					return strcmp($a["DateOfBirth"], $b["DateOfBirth"]);
				});
				$showDoB = '';
			}	
			if (isset($sortby) && $sortby == "youngest") {
				usort($whoarray, function($a, $b) {
					return strcmp($b["DateOfBirth"], $a["DateOfBirth"]);
				});
				$showDoB = '';
			}
			if (isset($sortby) && $sortby == "consta") {
				usort($whoarray, function($a, $b) {
					return strcmp($a["constituency"], $b["constituency"]);
				});
			}	
			if (isset($sortby) && $sortby == "constz") {
				usort($whoarray, function($a, $b) {
					return strcmp($b["constituency"], $a["constituency"]);
				});
			}			

			// Generate the list of questions 	
			for($j=0; $j < $length; $j++) {	
				
				if(!isset($showJoined)) {
					$showJoined = ' style="display:none;" ';
				}
				if(!isset($showDoB)) {
					$showDoB = ' style="display:none;" ';
				}
				
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
						$positionecho = '<div class="list-group-item joblist" style="display:block">'.$Govecho.$Oppecho.$Parecho.$Comecho.' 
										</div>';
					} else {
						$positionecho = "";
					}
					if(isset($department)){
						$positionecho = '<div class="list-group-item joblist"><h4 class="list-group-item-heading post">'.$whoarray[$j]["HansardName"].'</h4> 
										</div>';
					}
				
					if(isset($photos) && $photos == "screenshot"){
						$ifscreenshot = " screenshot";
					} else {
						$ifscreenshot = "";
					}
					
					if(!isset($party)) {
						$display = "yes";
					} else {
						if($party == $whoarray[$j]["Party"]){
							$display = "yes";
						} else { 
							$display = "no";
						}
					}
					
					if($display == "yes") {
						$hint=$hint .'		
							<div class="bootcards-cards bootcards-group">
								<div id="contactCard" class="guesswho">
									<div class="panel panel-default">
										<div class="panel-heading clearfix">
											<a href="search.php?m='.$whoarray[$j]["MemberId"].'" class="whonamelink"><h3 class="panel-title pull-left">'.$whoarray[$j]["DisplayAs"].'</h3></a href>
										</div>
										<div class="list-group">
											<div class="list-group-item list-group-item-image">
												<img src="'.$whoarray[$j]["imageurl"].'" class="group-member-image'.$ifscreenshot.'">
											</div>
											<div class="list-group-item party" style="background-color:'.$whoarray[$j]["color"].'" id="constituency">
												<h4 class="list-group-item-heading">'.$whoarray[$j]["Party"].'</h4>
											</div>
											<div class="list-group-item constituency">
												<h4 class="list-group-item-heading">'.$whoarray[$j]["constituency"].'</h4>
											</div>
											'.$positionecho.'
											<div class="list-group-item"'.$showJoined.'>
												<h4 class="list-group-item-heading post">Started: '.date('Y-m-d',strtotime($whoarray[$j]["StartDate"])).'</h4>
											</div>
											<div class="list-group-item"'.$showDoB.'>
												<h4 class="list-group-item-heading post">DOB: '.date('Y-m-d',strtotime($whoarray[$j]["DateOfBirth"])).'</h4>
											</div>
										</div>
									</div>
								</div><!--end contact card-->
							</div>';
					}		
				}
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