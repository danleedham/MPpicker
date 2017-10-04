<?php

/* whojobs.php 
*  Version 1.0 
*  04/10/2017 
*  This file generates the list of currently held jobs in the chosen house.
*  Output is a list of option HTML tags
*/

	if(!isset($house) && isset($_GET['house'])){
		$house = $_GET["house"];
	}
	if(!isset($house) or ($house == "Commons")){
		$house = "Commons";
	}
	if(isset($house) && ($house == "Lords")){
		$house = "Lords";
	}
	
	// Side allows the file to only query 
	if(!isset($side) && isset($_GET['side'])){
		$side = $_GET["side"];
	}
	if(isset($side) && $side == "government") {
		$sideURL = "GovernmentPosts";
	} elseif (isset($side) && $side == "opposition") {
		$sideURL = "OppositionPosts";
	} else  {
		$sideURL = "GovernmentPosts%7COppositionPosts";
	}
	if(!isset($side)){
		$side = "both";
	}
		
	$url='http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House='.$house.'%7CIsEligible=true/'.$sideURL.'/';

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
		
		$OppositionPostsList[] = ucwords($NameString);		
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
	$Jobsarray = array_filter(array_unique($Jobsarray, SORT_STRING));
	
	foreach ($Jobsarray as $value) {
		echo '<option value="'.$value.'">'.$value.'</option>';
	}
?>  

