<?php

/* int-numberofmembers.php 
*  Version 1.0 
*  04/10/2017 
*  This file generates the number of members from the chosen house
*/

if(!isset($house) && isset($_GET["house"])){
	$house = $_GET["house"];
}
if(!isset($house)){
	$house = "Commons";
}

$xmlDoc=new DOMDocument();
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/HouseOverview/".$house."/".date("Y-m-d")."/");
	$x=$xmlDoc->getElementsByTagName('Party');
	$partycount = $x->length;	
	if ($partycount == 0) {
	}
	else {	
		$allcount = 0;
		for($i=0; $i<($x->length); $i++) {			
			$TotalCount = $x->item($i)->getElementsByTagName('TotalCount');
			$allcount = intval($TotalCount->item(0)->textContent)+$allcount;

		}
	echo $allcount;
	}
?>
