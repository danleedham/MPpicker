<?php
	$xmlDoc=new DOMDocument();

	//get the q parameter from URL
	if(isset($_GET["q"])){$q=$_GET["q"];}

	if(!isset($house) && isset($_GET['house'])){
		$house = $_GET["house"];
	}
	if(!isset($house) or ($house == "Commons")){
		$house = "Commons";
	}
	if(isset($house) && ($house == "Lords")){
		$house = "Lords";
	}
	if(!isset($side) && isset($_GET['side'])){
		$side = $_GET["side"];
	}
	if(!isset($side)){
		$side = "both";
	}
	
	$url='http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House='.$house.'%7CIsEligible=true/GovernmentPosts%7COppositionPosts/';

	$xmlDoc=new DOMDocument();
		$xmlDoc->load($url);

	$GovernmentPostsList = array();
	$x=$xmlDoc->getElementsByTagName('GovernmentPost');
	for($i=0; $i<($x->length); $i++) {
		$Name=$x->item($i)->getElementsByTagName('HansardName');
		$NameString = trim($Name->item(0)->textContent);
		
		if (strpos(strtolower($NameString), strtolower($q)) !== false) {
			$GovernmentPostsList[] = ucwords($NameString);		
		}
	}
	$OppositionPostsList = array();
	$y=$xmlDoc->getElementsByTagName('OppositionPost');
	for($i=0; $i<($y->length); $i++) {
		$Name=$y->item($i)->getElementsByTagName('HansardName');
		$NameString = trim($Name->item(0)->textContent);
		if (strpos(strtolower($NameString), strtolower($q)) !== false) {
			$OppositionPostsList[] = ucwords($NameString);	
		}	
	}
	
	if($side == "both") {
		$Jobsarray = array_merge($GovernmentPostsList,$OppositionPostsList);
	} elseif ($side == "government") {
		$Jobsarray = $GovernmentPostsList;
	} elseif ($side == "opposition") {
		$Jobsarray = $OppositionPostsList;
	} else {
		$Jobsarray = array_merge($GovernmentPostsList,$OppositionPostsList);
	}
	
	sort($Jobsarray);
	$Jobsarray = array_values(array_filter(array_unique($Jobsarray, SORT_STRING)));

	// Get just the Member sub-elements of the XML File
	$x=$xmlDoc->getElementsByTagName('Member');
	
	if (strlen($q)>1) {
			$hint="";
			for($i=0; $i<($x->length); $i++) {
				$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
				$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
				$Party=$x->item($i)->getElementsByTagName('Party');
				$MemberId=$x->item($i)->getAttribute('Member_Id');
				$DodsId=$x->item($i)->getAttribute('Dods_Id');
				$Const=$x->item($i)->getElementsByTagName('MemberFrom');
				$Pos=$x->item($i)->getElementsByTagName('HansardName');	
				$Jobsarray = array();			
				for($j=0; $j<($Pos->length); $j++) {
					$Jobsarray[]=$Pos->item($j)->nodeValue;
				}
				$Jobsarray = array_filter(array_unique($Jobsarray, SORT_STRING));
				
				$JobsarrayForDisplay = "";
				foreach ($Jobsarray as $value) {
					if (strpos(strtolower($value), strtolower($q)) !== false) {
						$JobsarrayForDisplay = $JobsarrayForDisplay.$value."<br />";
					}
				}
				$ifactive = "";
				if (isset($mselected) && $mselected == $MemberId){ 
					$ifactive = " active";
				}
				if ($FullTitle->item(0)->nodeType==1) {
					//find a link matching the search text
					if (strpos(strtolower(implode($Jobsarray)),strtolower($q))) {
						$hint=$hint .'<a id="m'.$MemberId.'" class="list-group-item'.$ifactive.'" onclick="load('.$MemberId.');return false;" href="#">
						<img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded mini-member-image pull-left">
						<h4 class="list-group-item-heading"> '.$JobsarrayForDisplay.'</h4>
						<p class="list-group-item-text">'.
						$KnownAs->item(0)->childNodes->item(0)->nodeValue ." - ".$Party->item(0)->childNodes->item(0)->nodeValue."</p></a>";
					}
				}
			}
	} else {
	// They've asked us to search by something we've not yet implemented. 
		$hint = "Cannot search by ".$searchby;
	}

	// Set output if no members were found or to the correct values
	if ($hint=="") {
		$response='<a class="list-group-item">
		<h4 class ="list-group-item-heading">Please try again</h4></a>';
	} else {
	  $response=$hint;
	}

	//output the response
	echo $response;

?>