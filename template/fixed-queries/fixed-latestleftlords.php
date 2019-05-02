<?php

/* 
*  This file generates the date of the last person to leave the House of Lords
*/


$endDates = Array();
$xmlDoc=new DOMDocument();

// Get people who've deceased within the last 90 days
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7Cdeceaseddays=90/");
$x=$xmlDoc->getElementsByTagName('HouseEndDate');
$count = $x->length;

for($i=0; $i<$count; $i++){
    $endDates[] = $x->item($i)->textContent;    
}

// Now get Retired people
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7Cretireddays=90/");
$x=$xmlDoc->getElementsByTagName('HouseEndDate');
$count = $x->length;

for($i=0; $i<$count; $i++){
    $endDates[] = $x->item($i)->textContent;    
}

if(count($endDates) > 0) {
    sort($endDates);
    $latestLeft = $endDates[count($endDates)-1];
    echo str_replace("T00:00:00","",$latestLeft);
}

?>
