<?php
	if(!isset($house) && isset($_GET['house'])){
		$house = $_GET["house"];
	}
	if(!isset($house) or ($house == "Commons")){
		$house = "Commons";
		$url="http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/GovernmentPosts%7COppositionPosts/";
	}
	if(isset($house) && ($house == "Lords")){
		$url="http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7CIsEligible=true/GovernmentPosts%7COppositionPosts/";
	}
	
	$xmlDoc=new DOMDocument();
		$xmlDoc->load($url);

	$x=$xmlDoc->getElementsByTagName('GovernmentPost');
	for($i=0; $i<($x->length); $i++) {
		$Name=$x->item($i)->getElementsByTagName('HansardName');
		$NameString = trim($Name->item(0)->textContent);
		
		$GovernmentPostsList[] = ucwords($NameString);		
	}

	$y=$xmlDoc->getElementsByTagName('OppositionPost');
	for($i=0; $i<($y->length); $i++) {
		$Name=$y->item($i)->getElementsByTagName('HansardName');
		$NameString = trim($Name->item(0)->textContent);
		
		$GovernmentPostsList[] = ucwords($NameString);		
	}
	
	$OppositionPostsList = array();
	
	$Jobsarray = array_merge($GovernmentPostsList,$OppositionPostsList);
	sort($Jobsarray);
	$Jobsarray = array_filter(array_unique($Jobsarray, SORT_STRING));
	
	foreach ($Jobsarray as $value) {
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>  

