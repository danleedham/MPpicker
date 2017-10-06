<?php
$xmlDoc=new DOMDocument();

// Get values from URL
if(isset($_GET["house"])){$house=$_GET["house"];}
if(!isset($house)) {$house="Commons";} 
if(isset($_GET['m'])){$m = $_GET["m"];}
if(!isset($m)) { $m = "8";}

// Load members of the cabinet
$filename = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."|IsEligible=true|holdscabinetpost=true/";
$xmlDoc->load($filename);

// Each element of the XML is named Member. Split them so we can loop through them
$x=$xmlDoc->getElementsByTagName('Member');


$hint="";
for($i=0; $i<($x->length); $i++) {
	$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
	$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
	$Party=$x->item($i)->getElementsByTagName('Party');
	$MemberId=$x->item($i)->getAttribute('Member_Id');
	$DodsId=$x->item($i)->getAttribute('Dods_Id');
	$Const=$x->item($i)->getElementsByTagName('MemberFrom');
	$ifactive = "";
	if ($m == $MemberId){ 
		$ifactive = " active";
	}
	if ($FullTitle->item(0)->nodeType==1) {
		$hint=$hint .'<a id="m'.$MemberId.'" class="list-group-item'.$ifactive.'" onclick="load('.$MemberId.');return false;" href="#">
			<img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded mini-member-image pull-left">
			<h4 class="list-group-item-heading"> '.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>
			<p class="list-group-item-text">'.
			$Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</p></a>";
	}
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