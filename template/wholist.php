<?php
		//get parameters from URL
		$house=$_GET["house"];
			if ($house != "") { $houseurl = "House=".$house.'|'; }
			if (!$house) { $houseurl = ""; }
			if ($house == "both") { $houseurl = ""; }
			if ($house == "commons") { 
				$memberimgurlpre = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/";
				$memberimgurlsuf = ""; 
			}
			else { 
				$memberimgurlpre = "https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/";
				$memberimgurlsuf = ".jpg.jpg"; }
		$sex=$_GET["sex"];
			if ($sex != "") { $sexurl = "gender=".$sex.'|'; }
			if ($sex === "all") { $sexurl = ""; }
			if (!$sex) { $sexurl = ""; }
		
		$party=$_GET["party"];
			if ($party != "") { $partyurl = "party=".$party.'|'; }
			if (!$party) { $partyurl = ""; }
		
		$positions=$_GET["positions"];
			if (!$positions) { $positionsurl = ""; }
			elseif ($positions == "all") { $positionsurl = ""; }
			elseif ($positions == "cabinet") { $positionsurl = "holdscabinetpost=true".'|'; }
			elseif ($positions == "shadow") { $positionsurl = "holdsshadowcabinetpost=true".'|'; }
			elseif ($positions == "government") { $positionsurl = "holdsgovernmentpost=true".'|'; }
			elseif ($positions == "opposition") { $positionsurl = "holdsoppositionpost=true".'|'; }
			elseif ($positions == "parliamentary") { $positionsurl = "holdsparliamentarypost=true".'|';}
			elseif ($positions == "none") { $positionsurl = "holdsoppositionpost=false|holdsgovernmentpost=false".'|'; }
		
		$committee=$_GET["committee"];
			if (!$committee) { $committeeurl = ""; }
			if ($committee == "") { $committeeurl = ""; }
			if ($committee != "") { $committeeurl = "committee=".$committee.'|';}
		
		$department=$_GET["department"];
			if (!$department) { $departmenturl = ""; }
			if ($department == "") { $departmenturl = "";}
		
		$sortby=$_GET["sortby"];
		
		if ($house != "" ) {
		$xmlresults=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true|".$houseurl.$partyurl.$sexurl.$positionsurl.$committeeurl."/GovernmentPosts|OppositionPosts");
		$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true|".$houseurl.$partyurl.$sexurl.$positionsurl.$committeeurl."/GovernmentPosts|OppositionPosts");
		$x=$xmlDoc->getElementsByTagName('Member');
		
		$xarray = json_decode(json_encode($x), true);
		
		usort($xarray, function($a, $b) {
			return strcmp($a['HouseStartDate'] - $b['HouseStartDate']);
		});
        
        $colors = array("0"=>"#000000","4"=>"#0087DC","7"=>"#D46A4C","8"=>"#DDDDDD","15"=>"#DC241f","17"=>"#FDBB30","22"=>"#008142","29"=>"#FFFF00","30"=>"#008800","31"=>"#99FF66","35"=>"#70147A","38"=>"#9999FF","44"=>"#6AB023","47"=>"#FFFFFF");	


        $hint = "";

		for($i=0; $i<($x->length); $i++) {
				$FullTitle=$x->item($i)->getElementsByTagName('FullTitle');
				$KnownAs=$x->item($i)->getElementsByTagName('DisplayAs');
				$Party=$x->item($i)->getElementsByTagName('Party');
					foreach ($Party as $PartiesForId){ 
							$PartyID = $PartiesForId->getAttribute('Id'); }
							$MemberId=$x->item($i)->getAttribute('Member_Id');
							$DodsId=$x->item($i)->getAttribute('Dods_Id');
							$Const=$x->item($i)->getElementsByTagName('MemberFrom');
					
							$Govecho = "";
							$Oppecho = "";
							$Parecho = "";
							$Comecho = "";
		
			if ($house == "commons") { 
				$imgid = $MemberId; 
			}
			else {
				$imgid = $DodsId;
			}
		
		
			for($ii = 0; $ii < count($xmlresults->Member[$i]->GovernmentPosts[0]); $ii ++) {
				if (!strtotime($xmlresults->Member[$i]->GovernmentPosts->GovernmentPost[$ii]->EndDate[0]) >= time()) {
					$Government = $xmlresults->Member[$i]->GovernmentPosts->GovernmentPost[$ii]->HansardName[0]; 
					$Govecho = '<h4 class="list-group-item-heading">'. $Government . "</h4>";
					}
				} 			
				// let's see if they're currently a member of the opposition
				for($ii = 0; $ii < count($xmlresults->Member[$i]->OppositionPosts[0]); $ii ++) {
					if (!strtotime($xmlresults->Member[$i]->OppositionPosts->OppositionPost[$ii]->EndDate[0]) >= time()) {
						$Opposition = $xmlresults->Member[$i]->OppositionPosts->OppositionPost[$ii]->HansardName[0]; 
						$Oppecho = '<h4 class="list-group-item-heading">'. $Opposition . "</h4>";
					}
				}
				// let's see if they've got a parliamentary post
				for($ii = 0; $ii < count($xmlresults->Member[$i]->ParliamentaryPosts[0]); $ii ++) {
					if (!strtotime($xmlresults->Member[$i]->ParliamentaryPosts->ParliamentaryPost[$ii]->EndDate[0]) >= time()) {
						$Parliamentary = $xmlresults->Member[$i]->ParliamentaryPosts->ParliamentaryPost[$ii]->Name[0]; 
						$Parecho = '<h4 class="list-group-item-heading">'. $Parliamentary . "</h4>";
					}
				}
				// let's see if they're currently on any committees
				for($ii = 0; $ii < count($xmlresults->Member[$i]->Committees[0]); $ii ++) {
					if (!strtotime($xmlresults->Member[$i]->Committees->Committee[$ii]->EndDate[0]) >= time()) {
						$Committee = $xmlresults->Member[$i]->Committees->Committee[$ii]->Name[0]; 
						$Comecho = '<h4 class="list-group-item-heading">'.$Committee . "</h4>";
					}
				} 		
			
				if ($hint=="") {
				  $hint='<div class="col-sm-3 bootcards-cards bootcards-group"><div id="contactCard"><div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h3 class="panel-title pull-left">'. $KnownAs->item(0)->childNodes->item(0)->nodeValue.'</h3>
						</div>
					  <div class="list-group">
						<div class="list-group-item">
						  <img src="'.$memberimgurlpre.$imgid.$memberimgurlsuf.'" class="img-rounded group-member-image">
						 </div>
						 <div class="list-group-item">
						  <h4 class="list-group-item-heading" style="color:'
						  .$colors[intval($PartyID)].'">'.$Party->item(0)->childNodes->item(0)->nodeValue.'</h4>
						</div>
						<div class="list-group-item">
						  <h4 class="list-group-item-heading">'.$Const->item(0)->childNodes->item(0)->nodeValue.'</h4>
						</div>
						<div class="list-group-item">'.$Govecho.$Oppecho.$Parecho.$Comecho.' 
						</div>
					  </div>
					  </div>

					</div><!--contact card-->

				</div>';	
				} 
			else {
				  $hint=$hint .'<div class="col-sm-3 bootcards-cards bootcards-group"><div id="contactCard"><div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h3 class="panel-title pull-left">'. $KnownAs->item(0)->childNodes->item(0)->nodeValue.'</h3>
						</div>
					  <div class="list-group">
						<div class="list-group-item">
						  <img src="'.$memberimgurlpre.$imgid.$memberimgurlsuf.'" class="img-rounded group-member-image">
						 </div>
						 <div class="list-group-item">
						  <h4 class="list-group-item-heading" style="color:'
						  .$colors[intval($PartyID)].'">'.$Party->item(0)->childNodes->item(0)->nodeValue.'</h4>
						</div>
						<div class="list-group-item">
						  <h4 class="list-group-item-heading">'.$Const->item(0)->childNodes->item(0)->nodeValue.'</h4>
						</div>
					   <div class="list-group-item">'.$Govecho.$Oppecho.$Parecho.$Comecho.' 
					  </div>
						 
					  </div>
					  </div>

					</div><!--contact card-->

				</div>';
				}
			}
	    
		if ($hint=="") {
		  $response='<div class="col-sm-12 bootcards-cards bootcards-group">
					   <div id="contactCard">
						  <div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left">Please try again</h3>
							</div>
						  </div>
						</div>
					  </div>';
		} else {
		  $response=$hint;
		}
		
		//output the response
		echo $response;

	  ?>