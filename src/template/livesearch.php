<?php
	$xmlDoc=new DOMDocument();

	//get the q parameter from URL
	if(isset($_GET["q"])){$q=$_GET["q"];}

	//if house is passed from URL set it or choose Commons
	if(isset($_GET["house"])){$house=$_GET["house"];}
	if(!isset($house)) {$house="Commons";} 

	//if searchby is passed from URL set it or choose name
	if(isset($_GET["searchby"])){$searchby=$_GET["searchby"];}
	if(!isset($searchby)) {$searchby="name";}

	//if a selected member is passed from URL set it
	if(isset($_GET["mselected"])){$mselected=$_GET["mselected"];}

	// Load the right XML file for whatever we're searching for
	if ($searchby == "constituency") {
		//Load all the members who are eligible to sit, then we will compare their constituencies later, as the query doesn't allow like constituency *
		$filename = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."|IsEligible=true/";
		$xmlDoc->load($filename);
		
	} elseif ($searchby == "position") {
		if(!isset($side) && isset($_GET['side'])){
			$side = $_GET["side"];
		}
		if(!isset($side)){
			$side = "both";
		}
				
		if(isset($side) && $side !== "both") {
			if($side == "opposition") {
				$sideURL = "OppositionPosts";	
			} else {
				$sideURL = "GovernmentPosts";
			}	

		} else {
			$side = "both";
			$sideURL = "GovernmentPosts%7COppositionPosts";
		}
		//Load all the members who are eligible to sit, then we will compare their constituencies later, as the query doesn't allow like constituency *
		$filename = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."%7CIsEligible=true/".$sideURL."/";
		$xmlDoc->load($filename);

	} else { 
		//Load the members who are eligible to sit and with names like the query
		$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."|IsEligible=true|name*".$q);
	}

	// Get just the Member sub-elements of the XML File
	$x=$xmlDoc->getElementsByTagName('Member');
	
	// Array with party ID and party color
	require_once('colors.php');	
	
    // If beta images are loaded prior to this then skip
    if(!isset($feed)){
        $feed = file_get_contents("betaimages.xml");
        $betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
        $imagescount = count($betaimages);
    }
	
	//First, lets look for members by name
	if ($searchby == "name") {
		if(strlen($q)>1) {
			$hint="";
			for($i=0; $i<($x->length); $i++) {
				$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
				$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
				$Party=$x->item($i)->getElementsByTagName('Party');
				$PartyID=$x->item($i)->getElementsByTagName('Party')->item(0)->getAttribute('Id');
				$color = $colors[intval($PartyID)];
				$MemberId=$x->item($i)->getAttribute('Member_Id');
				$DodsId=$x->item($i)->getAttribute('Dods_Id');
				$Const=$x->item($i)->getElementsByTagName('MemberFrom');
				$ifactive = "";
				if (isset($mselected) && $mselected == $MemberId) { 
					$ifactive = " active";
				}
				if($house !== "Commons") {
				    $imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
				} else { 
                    for($ii=0; $ii < $imagescount; $ii++) {
                        if (intval($betaimages->member[$ii]->memberid) == $MemberId){
                            $BetaId = $betaimages->member[$ii]->imageid;
                            $imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
                        }
                    }
                    if (!isset($BetaId) or $BetaId == ""){
                        $imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$MemberId;
                    }
                }
				if ($FullTitle->item(0)->nodeType==1) {
					//find a link matching the search text
					if (stristr($FullTitle->item(0)->childNodes->item(0)->nodeValue,$q)) {
						$hint = $hint .'<a id="m'.$MemberId.'" class="list-group-item list-section-list'.$ifactive.'" onclick="searchload('.$MemberId.');return false;" href="#">
						<img src="'.$imageurl.'" class="mini-member-image pull-left">
						<h4 class="list-group-item-heading"> <span class="partybox" style="background:'.$color.'!important"></span>'.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>'.
						$Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</a>";
					}
				}
			}
		}
	} elseif ($searchby == "constituency"){
		// Now for the case that it's search by constituency
		if (strlen($q)>1) {
			$hint="";
			for($i=0; $i<($x->length); $i++) {
				$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
				$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
				$Party=$x->item($i)->getElementsByTagName('Party');
				$PartyID=$x->item($i)->getElementsByTagName('Party')->item(0)->getAttribute('Id');
				$color = $colors[intval($PartyID)];
				$MemberId=$x->item($i)->getAttribute('Member_Id');
				$DodsId=$x->item($i)->getAttribute('Dods_Id');
				
				if ($house == "Lords") {
					$Const=$FullTitle; //fix to search for the Lords name for location as they don't have constituencies
				} else {
					$Const=$x->item($i)->getElementsByTagName('MemberFrom');
				}
				if($house !== "Commons") {
				    $imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
				} else { 
                    for($ii=0; $ii < $imagescount; $ii++) {
                        if (intval($betaimages->member[$ii]->memberid) == $MemberId){
                            $BetaId = $betaimages->member[$ii]->imageid;
                            $imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
                        }
                    }
                    if (!isset($BetaId) or $BetaId == ""){
                        $imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$MemberId;
                    }
                }
				
				$ifactive = "";
				if (isset($mselected) && $mselected == $MemberId){ 
					$ifactive = " active";
				}
				if ($FullTitle->item(0)->nodeType==1) {
					//find a link matching the search text
					if (stristr($Const->item(0)->childNodes->item(0)->nodeValue,$q)) {
						$hint=$hint .'<a id="m'.$MemberId.'" class="list-group-item list-section-list'.$ifactive.'" onclick="searchload('.$MemberId.');return false;" href="#">
						<img src="'.$imageurl.'" class="mini-member-image pull-left">
						<h4 class="list-group-item-heading"><span class="partybox" style="background:'.$color.'!important"></span>'.$KnownAs->item(0)->childNodes->item(0)->nodeValue .'</h4>'.
						$Party->item(0)->childNodes->item(0)->nodeValue.' ('.$Const->item(0)->childNodes->item(0)->nodeValue.")</a>";
					}
				}
			}
		}
	} elseif ($searchby == "position"){
	// Now for the case that it's search by position
		if (strlen($q)>1) {
			$hint="";
			for($i=0; $i<($x->length); $i++) {
				$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
				$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
				$Party=$x->item($i)->getElementsByTagName('Party');
				$PartyID=$x->item($i)->getElementsByTagName('Party')->item(0)->getAttribute('Id');
				$color = $colors[intval($PartyID)];
				$MemberId=$x->item($i)->getAttribute('Member_Id');
				$DodsId=$x->item($i)->getAttribute('Dods_Id');
				$Const=$x->item($i)->getElementsByTagName('MemberFrom');
				$Pos=$x->item($i)->getElementsByTagName('HansardName');	
				$Jobsarray = array();			
				for($j=0; $j<($Pos->length); $j++) {
					$Jobsarray[]=$Pos->item($j)->nodeValue;
				}
				$Jobsarray=array_filter(array_unique($Jobsarray, SORT_STRING));
				
				$JobsarrayForDisplay = "";
				foreach ($Jobsarray as $value) {
					$JobsarrayForDisplay = $JobsarrayForDisplay.$value."<br />";
				}
				if($house !== "Commons") {
				    $imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
				} else { 
                    for($ii=0; $ii < $imagescount; $ii++) {
                        if (intval($betaimages->member[$ii]->memberid) == $MemberId){
                            $BetaId = $betaimages->member[$ii]->imageid;
                            $imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
                        }
                    }
                    if (!isset($BetaId) or $BetaId == ""){
                        $imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$MemberId;
                    }
                }				
				$ifactive = "";
				if (isset($mselected) && $mselected == $MemberId){ 
					$ifactive = " active";
				}
				if ($FullTitle->item(0)->nodeType==1) {
					//find a link matching the search text
					if (strpos(strtolower(implode($Jobsarray)),strtolower($q))) {
						$hint=$hint .'<a id="m'.$MemberId.'" class="list-group-item list-section-list'.$ifactive.'" onclick="searchload('.$MemberId.');return false;" href="#">
						<img src="'.$imageurl.'" class="mini-member-image pull-left">
						<h4 class="list-group-item-heading list-group-position"> <span class="partybox" style="background:'.$color.'!important"></span>'.$JobsarrayForDisplay.'</h4>'.
						$KnownAs->item(0)->childNodes->item(0)->nodeValue ." - ".$Party->item(0)->childNodes->item(0)->nodeValue."</a>";
					}
				}
			}
		}
	} else {
	// They've asked us to search by something we've not yet implemented. 
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