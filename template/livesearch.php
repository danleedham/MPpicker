<?php
$xmlDoc=new DOMDocument();

//get the q parameter from URL
$q=$_GET["q"];
$house=$_GET["house"];
if(!$house) {$house="Commons";} 
if($house == "") {$house="Commons";} 
$searchby=$_GET["searchby"];
if(!$searchby) {$searchby="name";}
if($searchby == "") {$searchby="name";} 
$mselected=$_GET["mselected"];

//Lets load the members who are eligible to sit and with names like the query
if ($searchby == "constituency") {
		$filename = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."|IsEligible=true/";
		$xmlDoc->load($filename);
	}
else { 	
		$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."|IsEligible=true|name*".$q);
}


	$x=$xmlDoc->getElementsByTagName('Member');

//First, lets look for members by name

//lookup all links from the xml file if length of 2 or more
	if ($searchby == "name") {
	if (strlen($q)>1) {
	  $hint="";
	  for($i=0; $i<($x->length); $i++) {
		$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
		$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
		$Party=$x->item($i)->getElementsByTagName('Party');
		$MemberId=$x->item($i)->getAttribute('Member_Id');
		$DodsId=$x->item($i)->getAttribute('Dods_Id');
		$Const=$x->item($i)->getElementsByTagName('MemberFrom');
		$ifactive = "";
		if ($mselected == $MemberId)
			{ $ifactive = " active";
			}
		if ($FullTitle->item(0)->nodeType==1) {
		  //find a link matching the search text
		  if (stristr($FullTitle->item(0)->childNodes->item(0)->nodeValue,$q)) {
			if ($hint=="") {
			  $hint='<a id="m'.$MemberId.'" class="list-group-item'.$ifactive.'" onclick="load('.$MemberId.');return false;" href="#">
				  <img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded mini-member-image pull-left">
				  <h4 class="list-group-item-heading"> '.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>
				  <p class="list-group-item-text">'.
			  $Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</p></a>";
			} else {
			  $hint=$hint .'<a id="m'.$MemberId.'" class="list-group-item'.$ifactive.'" onclick="load('.$MemberId.');return false;" href="#">
				  <img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded mini-member-image pull-left">
				  <h4 class="list-group-item-heading"> '.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>
				  <p class="list-group-item-text">'.
			  $Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</p></a>";
			}
		  }
		}
	  }
	}
	}
	// Now for the case that it's search by cons
	elseif ($searchby == "constituency")
	{
	if (strlen($q)>1) {
	  $hint="";
	  for($i=0; $i<($x->length); $i++) {
		$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
		$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
		$Party=$x->item($i)->getElementsByTagName('Party');
		$MemberId=$x->item($i)->getAttribute('Member_Id');
		$DodsId=$x->item($i)->getAttribute('Dods_Id');
		if ($house == "Lords") {
		$Const=$FullTitle; //fix to search for the Lords name for location as they don't have constituencies
		}
		else {
		$Const=$x->item($i)->getElementsByTagName('MemberFrom');
		}
		$ifactive = "";
		if ($mselected == $MemberId)
			{ $ifactive = " active";
			}
		if ($FullTitle->item(0)->nodeType==1) {
		  //find a link matching the search text
		  if (stristr($Const->item(0)->childNodes->item(0)->nodeValue,$q)) {
			if ($hint=="") {
			  $hint='<a id="m'.$MemberId.'" class="list-group-item'.$ifactive.'" onclick="load('.$MemberId.');return false;" href="#">
				  <img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded mini-member-image pull-left">
				  <h4 class="list-group-item-heading"> '.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>
				  <p class="list-group-item-text">'.
			  $Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</p></a>";
			} else {
			  $hint=$hint .'<a id="m'.$MemberId.'" class="list-group-item'.$ifactive.'" onclick="load('.$MemberId.');return false;" href="#">
				  <img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded mini-member-image pull-left">
				  <h4 class="list-group-item-heading"> '.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>
				  <p class="list-group-item-text">'.
			  $Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</p></a>";
			}
		  }
		}
	  }
	}
	}
	else {
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