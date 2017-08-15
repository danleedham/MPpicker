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
		if ($house == "commons") { 
			$memberimgurlpre = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/";
			$memberimgurlsuf = ""; 
		}
		else { 
			$memberimgurlpre = "https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/";
			$memberimgurlsuf = ".jpg.jpg"; 
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
			$returnpositions = "GovernmentPosts|OppositionPosts";
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
		if (!$department) { 
			$departmenturl = ""; 
		}
		if ($department == "") { 
			$departmenturl = "";
		}
		
	// Which sort function to use prior to rendering
	$sortby=$_GET["sortby"];
	
	// Now load things
	if ($house !== "" ) {
		$url = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true".$houseurl.$partyurl.$genderurl.$positionsurl.$committeeurl."/".$returnpositions.$returncommittee."BiographyEntries";
	// echo $url;
		$xmlDoc->load($url);
		$x=$xmlDoc->getElementsByTagName('Member');
	
		$colors = array("0"=>"#000000","4"=>"#0087DC","7"=>"#D46A4C","8"=>"#DDDDDD","15"=>"#DC241f","17"=>"#FDBB30","22"=>"#008142","29"=>"#FFFF00","30"=>"#008800","31"=>"#99FF66","35"=>"#70147A","38"=>"#9999FF","44"=>"#6AB023","47"=>"#FFFFFF");	

		$hint = "";
		
	// Let's build an array with our results in
	for($i=0; $i<($x->length); $i++) {
		
		$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
		$DisplayAs=$x->item($i)->getElementsByTagName('DisplayAs');
		$Party=$x->item($i)->getElementsByTagName('Party');
			foreach ($Party as $PartiesForId){ 
					$PartyID = $PartiesForId->getAttribute('Id'); 
			}
		$MemberId=$x->item($i)->getAttribute('Member_Id');
		$DodsId=$x->item($i)->getAttribute('Dods_Id');	
		// Fix for the Lords not having decent photos... yet!
		if ($house == "commons") { 
			$imgid = $MemberId; 
		}
		else {
			$imgid = $DodsId;
		}	
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
							  'Party'=>$Party[0]->textContent,
							  'PartyId'=>intval($PartyID),
							  'color'=>$colors[intval($PartyID)],
							  'DodsId'=>intval($DodsId),
							  'MemberId'=>intval($MemberId),
							  'imgid' => $imgid,
							  'constituency'=>$Const[0]->textContent,
							  'GovenmentPosts'=>$GovernmentPostsList,
							  'OppositionPosts'=>$OppositionPostsList,
							  'ParliamentaryPosts'=>$ParliamentaryPostsList,
							  'Committees'=>$CommitteesList);
	}
				
	// print_r($whoarray);
	
	// Function to sort members by x then by y
		function compareo($a, $b) {
			if ($a['type'] == $b['type']) {
				return $a['number'] - $b['number'];
			}
			return strcmp($a['type'], $b['type']);
		}
		// Count how many questions there are
		$length = count($whoarray);	
	
	// If there are questions, sort the questions & generate list
	if ($length !== 0) {
		//usort($whoarray, 'compareo');
			
		// Generate the list of questions 	
		for($j=0; $j < $length; $j++) {	
			$Govecho = "";
			if($whoarray[$j]["GovenmentPosts"]){
				foreach($whoarray[$j]["GovenmentPosts"] as $value) {
				   $Govecho=$Govecho.'<h4 class="list-group-item-heading">'. $value.'</h4>';
				}
			}
			$Oppecho = "";
			if($whoarray[$j]["OppositionPosts"]){
				foreach($whoarray[$j]["OppositionPosts"] as $value) {
				   $Oppecho=$Oppecho.'<h4 class="list-group-item-heading">'. $value.'</h4>';
				}
			}
			$Parecho = "";
			if($whoarray[$j]["ParliamentaryPosts"]){
				foreach($whoarray[$j]["ParliamentaryPosts"] as $value) {
				   $Parecho=$Parecho.'<h4 class="list-group-item-heading">'. $value.'</h4>';
				}
			}
			$Comecho = "";
			if($whoarray[$j]["Committees"]){
				foreach($whoarray[$j]["Committees"] as $value) {
				   $Comecho=$Comecho.'<h4 class="list-group-item-heading">'. $value.'</h4>';
				}
			}
			$hint=$hint .'		
				<div class="bootcards-cards bootcards-group">
					<div id="contactCard">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left">'.$whoarray[$j]["DisplayAs"].'</h3>
							</div>
							<div class="list-group">
								<div class="list-group-item">
									<img src="'.$memberimgurlpre.$whoarray[$j]["imgid"].$memberimgurlsuf.'" class="img-rounded group-member-image">
								</div>
								<div class="list-group-item">
									<h4 class="list-group-item-heading" style="color:'.$whoarray[$j]["color"].'">'.$whoarray[$j]["Party"].'</h4>
								</div>
								<div class="list-group-item">
									<h4 class="list-group-item-heading">'.$whoarray[$j]["constituency"].'</h4>
								</div>
								<div class="list-group-item">'.$Govecho.$Oppecho.$Parecho.$Comecho.' 
								</div>
							</div>
						</div>
					</div><!--end contact card-->
				</div>';

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