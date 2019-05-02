<?php

/* 
*  This file generates a list of the members of the House of Lords we've recently lost
*/


$lordsInfo = Array();
$xmlDoc=new DOMDocument();

// Get people who've deceased within the last 90 days
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7Cdeceaseddays=90/");
$x=$xmlDoc->getElementsByTagName('Member');
$count = $x->length;

for($i=0; $i<$count; $i++){
    $lordsInfo[$i]['Member_Id'] = $x->item($i)->getAttribute('Member_Id');
    $lordsInfo[$i]['Dods_Id'] = $x->item($i)->getAttribute('Dods_Id');
    $lordsInfo[$i]['DisplayAs'] = $x->item($i)->getElementsByTagName('DisplayAs')->item(0)->textContent;
    $lordsInfo[$i]['DateOfBirth'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('DateOfBirth')->item(0)->textContent);
    $lordsInfo[$i]['DateOfDeath'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('DateOfDeath')->item(0)->textContent);
    $lordsInfo[$i]['Party'] = $x->item($i)->getElementsByTagName('Party')->item(0)->textContent;
    $lordsInfo[$i]['PartyId'] = $x->item($i)->getElementsByTagName('Party')->item(0)->getAttribute('Id');
    $lordsInfo[$i]['MemberFrom'] = $x->item($i)->getElementsByTagName('MemberFrom')->item(0)->textContent;
    $lordsInfo[$i]['HouseStartDate'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('HouseStartDate')->item(0)->textContent);
    $lordsInfo[$i]['HouseEndDate'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('HouseEndDate')->item(0)->textContent);
    $lordsInfo[$i]['reason'] = 'Deceased';
    $lordsInfo[$i]['sortDate'] = $lordsInfo[$i]['DateOfDeath'];
}

$deadCount = count($lordsInfo);

// Now get Retired Lords
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Lords%7Cretireddays=90/");
$x=$xmlDoc->getElementsByTagName('Member');
$count = $x->length;

for($i=0; $i<$count; $i++){
    $lordsInfo[$deadCount+$i]['Member_Id'] = $x->item($i)->getAttribute('Member_Id');
    $lordsInfo[$deadCount+$i]['Dods_Id'] = $x->item($i)->getAttribute('Dods_Id');
    $lordsInfo[$deadCount+$i]['DisplayAs'] = $x->item($i)->getElementsByTagName('DisplayAs')->item(0)->textContent;
    $lordsInfo[$deadCount+$i]['DateOfBirth'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('DateOfBirth')->item(0)->textContent);
    $lordsInfo[$deadCount+$i]['DateOfDeath'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('DateOfDeath')->item(0)->textContent);
    $lordsInfo[$deadCount+$i]['Party'] = $x->item($i)->getElementsByTagName('Party')->item(0)->textContent;
    $lordsInfo[$deadCount+$i]['PartyId'] = $x->item($i)->getElementsByTagName('Party')->item(0)->getAttribute('Id');
    $lordsInfo[$deadCount+$i]['MemberFrom'] = $x->item($i)->getElementsByTagName('MemberFrom')->item(0)->textContent;
    $lordsInfo[$deadCount+$i]['HouseStartDate'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('HouseStartDate')->item(0)->textContent);
    $lordsInfo[$deadCount+$i]['HouseEndDate'] = str_replace("T00:00:00","",$x->item($i)->getElementsByTagName('HouseEndDate')->item(0)->textContent);
    $lordsInfo[$deadCount+$i]['reason'] = 'Retired';
    $lordsInfo[$deadCount+$i]['sortDate'] = $lordsInfo[$deadCount+$i]['HouseEndDate'];
}

// Let's do some sorting here
usort($lordsInfo, function($a, $b) {
    return $b['sortDate'] <=> $a['sortDate'];
});

// Now let's echo out our discoveries
$echo = "";

// If beta images are loaded prior to this then skip
if(!isset($feed)){
    $feed = file_get_contents("../betaimages.xml");
    $betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
    $imagescount = count($betaimages);
}
    
for($i=0; $i<count($lordsInfo); $i++){
    
    $imageurl = "";
    for($ii=0; $ii < $imagescount; $ii++) {
        if (intval($betaimages->member[$ii]->memberid) == $lordsInfo[$i]['Member_Id']){
            $BetaId = $betaimages->member[$ii]->imageid;
            $imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
        }
    }
    if ($imageurl == "" or !isset($BetaId)){
        $imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$lordsInfo[$i]['Dods_Id'].'.jpg.jpg';
    }
    
    require_once('../core/colors.php');	
    $color = $colors[intval($lordsInfo[$i]['PartyId'])];
    
    if($lordsInfo[$i]['reason'] == "Retired"){
        $service = date_diff(date_create($lordsInfo[$i]['HouseStartDate']),date_create($lordsInfo[$i]['HouseEndDate']));
        $reasonEcho = "Retired on ".$lordsInfo[$i]['HouseEndDate'].' after '.$service->format("%y").' years';
    } else {
        $age = date_diff(date_create($lordsInfo[$i]['DateOfDeath']),date_create($lordsInfo[$i]['DateOfBirth']));
        $service = date_diff(date_create($lordsInfo[$i]['HouseStartDate']),date_create($lordsInfo[$i]['HouseEndDate']));
        $reasonEcho = "Died on ".$lordsInfo[$i]['DateOfDeath']." aged ".$age->format("%y").' serving '.$service->format("%y%").' years';
    }
    $echo = $echo .'<a class="list-group-item list-section-list lords-lost-list" href="search.php?m='.$lordsInfo[$i]['Member_Id'].'">
    <img src="'.$imageurl.'" class="mini-member-image pull-left" alt="">
    <h4 class="list-group-item-heading"> <span class="partybox" style="background:'.$color.'!important"></span>'.$lordsInfo[$i]['DisplayAs'].'</h4>'.
    $lordsInfo[$i]['MemberFrom'].' ('.$lordsInfo[$i]['Party'].") ".$reasonEcho."</a>";
}
echo $echo;
?>
