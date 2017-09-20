<?php

	echo '<option value=""></option>';
	echo '<option value="">All</option>';
	$xmlDoc=new DOMDocument();
	$xmlDoc->load('http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/BiographyEntries/');
	$x=$xmlDoc->getElementsByTagName('BiographyEntry');
	$categoriescount = $x->length;	
	if ($categoriescount == 0) {
	} else {	
		for($i=0; $i<($x->length); $i++) {
			$Entry=$x->item($i)->getElementsByTagName('Entry');
			$EntryString = trim($Entry->item(0)->textContent);
			
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

