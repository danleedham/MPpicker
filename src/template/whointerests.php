<?php

/* whointerests.php 
*  Version 1.0 
*  04/10/2017 
*  This file generates the list of currently held interests in the chosen house.
*  Output is a list of option HTML tags
*/

	if(!isset($house) && isset($_GET['house'])){
		$house = $_GET["house"];
	}
	if(isset($house) && ($house == "Lords")){
		$house = "Lords";
	}
	if(!isset($house) or ($house == "Commons")){
		$house = "Commons";
	}

	echo '<option value=""></option>';
	echo '<option value="">All</option>';
	$xmlDoc=new DOMDocument();
	// Load just the current members BiographyEntries as that's what contains the interests
	
	$xmlDoc->load('http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House='.$house.'%7CIsEligible=true/BiographyEntries/');
	$x=$xmlDoc->getElementsByTagName('BiographyEntry');
	$categoriescount = $x->length;	
	if ($categoriescount == 0) {
	} else {	
		for($i=0; $i<($x->length); $i++) {
			$Entry=$x->item($i)->getElementsByTagName('Entry');
			$EntryString = trim($Entry->item(0)->textContent);
			
			$EntryString = str_replace(";",",",$EntryString);
			
			// Split each member's interest out
			$ArrayofEntries=explode(",", $EntryString);
			
			// Let's put all those into the master array and move on					 			
			foreach ($ArrayofEntries as $entry) {
				$interestsarray[] = ucwords(trim($entry));	
			}		
		}
		sort($interestsarray);
		$interestsarray = array_unique($interestsarray, SORT_STRING);	
		foreach ($interestsarray as $value) {
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
?>  

