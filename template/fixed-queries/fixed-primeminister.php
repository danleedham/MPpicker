<?php

/* 
*  This file generates the id number of the current Prime Minister
*/
if(isset($_GET['idtype'])){
    $idtype = $_GET['idtype'];
}  else {
    $idtype = 'Member_Id';
}

if(isset($_GET['echo'])){
    $echo = $_GET['echo'];
}  else {
    $echo = false;
}
$xmlDoc=new DOMDocument();

// Government Post ID for Prime Minister is 661
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/governmentpostid=661/");
$x=$xmlDoc->getElementsByTagName('Member');
$count = $x->length;

$pmId = $x->item(0)->getAttribute($idtype);

if($echo == true){
    echo $pmId;
}

?>
