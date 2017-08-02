<?php $xmlDoc=new DOMDocument(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

  <title>Guess Who?</title>

	<?php
		
		//get the css and js nonesense
		include 'template/headinc.php';
		
		//get the q parameter from URL
		$m=$_GET["m"];
		if (!$m){ $m="8";}
		$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
		$q=$_GET["q"];
		if (!$q){ $SearchContactsMessage="Search MPs..."; 
				}
			else { $SearchContactsMessage=$q; 
				}
				
		$house=$_GET["house"];
		if ($house != "") { $houseurl = "House=".$house.'|'; }
		if (!$house) { $houseurl = ""; }
		if ($house == "both") { $houseurl = ""; }
		if ($house == "commons") { $memberimgurlpre = "http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/";
		$memberimgurlsuf = ""; }
		else { $memberimgurlpre = "https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/";
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
		if ($positions == "all") { $positionsurl = ""; }
		if ($positions == "cabinet") { $positionsurl = "holdscabinetpost=true".'|'; }
		if ($positions == "shadow") { $positionsurl = "holdsshadowcabinetpost=true".'|'; }
		if ($positions == "government") { $positionsurl = "holdsgovernmentpost=true".'|'; }
		if ($positions == "opposition") { $positionsurl = "holdsoppositionpost=true".'|'; }
		if ($positions == "none") { $positionsurl = "holdsoppositionpost=false|holdsgovernmentpost=false".'|'; }
		$sortby=$_GET["sortby"];
		$committee=$_GET["committee"];
		if (!$committee) { $committeeurl = ""; }
		if ($committee == "") { $committeeurl = ""; }
		if ($committee != "") { $committeeurl = "committee=".$committee.'|';}
		$department=$_GET["department"];
		if (!$department) { $departmenturl = ""; }
		if ($department == "") { $departmenturl = "";}
		
		
		$partieslords=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/HouseOverview/Lords/".date("Y-m-d")."/");
		$partiescommons=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/HouseOverview/Commons/".date("Y-m-d")."/");
		if ($house != "" ) {
		$xmlresults=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true|".$houseurl.$partyurl.$sexurl.$positionsurl.$committeeurl."/GovernmentPosts|OppositionPosts");
		$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/IsEligible=true|".$houseurl.$partyurl.$sexurl.$positionsurl.$committeeurl."/GovernmentPosts|OppositionPosts");
		$x=$xmlDoc->getElementsByTagName('Member');
		
		$xarray = json_decode(json_encode($x), true);
		
		usort($xarray, function($a, $b) {
			return strcmp($a['HouseStartDate'] - $b['HouseStartDate']);
		});

		$resultsfound=count($xmlresults)." Members found"; 
						}  
		else { 
			$resultsfound="Please narrow search"; 
			}
		//Let's get a list of all current committees
		$com=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Committees/");
		
		$Committees = array();
		for($i = 0; $i < count($com); $i ++) {
			if (!strtotime($com->Committee[$i]->EndDate[0]) >= time()) {
				$Committees[] = $com->Committee[$i]->Name;
			}
		}

		$ComSorted = sort($Committees,2);
		
		//Let's get a list of all current departments
		$dept=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Departments/");
		
		$Departments = array();
			for($i = 0; $i < count($dept); $i ++) {
				if (!strtotime($dept->Department[$i]->EndDate[0]) >= time()) {
					$DepartmentName = $dept->Department[$i]->Name;
					$DepartmentId = $dept->Department[$i]->Department_Id;
					$DepartmentAcronym = $dept->Department[$i]->Acronym;
					$Departments[] = $dept->Department[$i]->Name;
					}
			}

			$DeptSorted = sort($Departments,2);
		
		//array for party colors
		include 'template/colors.php';				
								
		?>

</head>

<body>

   <div class="container-fluid bootcards-container push-right">

    <div class="row">
	 <!-- Search panel -->
      <!--options column-->
      <div class="col-sm-3 bootcards-list" id="list" data-title="Contacts">
        <div class="panel panel-default">  
         <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left"> <?php if ($resultsfound === "0" ) { echo "Guess Who?"; }
														 else { echo $resultsfound; }	?>
				</h3> <?php echo $selectedcommittee; ?>
              </div>     
	
		
			 <div class="list-group">
				<!-- start search form -->
				<form id="mpsearch" action="who.php" method="GET">	
					<!-- sex -->
					 <div class="list-group-item">
					 <select name="house" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="house">
					 <option value="">House</option>
				     <option value="commons" <?php if ($house === 'commons') { echo 'selected="selected"'; } ?>> Commons </option>
				     <option value="lords" <?php if ($house === 'lords') { echo 'selected="selected"'; } ?>> Lords </option>
				     <option value="both" <?php if ($house === 'both') { echo 'selected="selected"'; } ?>> Both </option>
				     </select>
				     </div>
				     	
					<!-- sex -->
					 <div class="list-group-item">
					 <select name="sex" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="sex">
					 <?php if(!$sex) {echo '<option value="">Sex</option>'; } ?>
				     <option value="m" <?php if ($sex === 'm') { echo 'selected="selected"'; } ?>> Male </option>
				     <option value="f" <?php if ($sex === 'f') { echo 'selected="selected"'; } ?>> Female </option>
				     <option value="all" <?php if ($sex === 'all') { echo 'selected="selected"'; } ?>> All </option>
				     </select>
				     </div>

					<!-- party -->		
					<div class="list-group-item">
					<select name="party" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="party">
						<option <?php if ($party === 'all') { echo 'selected="selected"'; } ?> value="all">All</option>
					<?php 
							if (!$party) { echo '<option value="" selected="selected">Party</option>'; }
							
							for($i = 0; $i < count($partieslords); $i ++) {
							$partyid = $partieslords->Party[$i]->attributes()->Id;
							$partyname = $partieslords->Party[$i]->Name;
							$partycount = $partieslords->Party[$i]->TotalCount;
							$partymale = $partieslords->Party[$i]->MaleCount;
							$partyfemale = $partieslords->Party[$i]->FemaleCount;
							$isselected = '';
						if ($party == $partyname)
							{ $isselected = 'selected="selected"'; 
							}
						echo '<option '.$isselected.' value="'.$partyname.'">'.$partyname.' - '.$partycount.'</option>';
				
						} 
					?>	 
					</select>
					</div>
					
					
					<!-- positions -->
					 <div class="list-group-item">
					 <select name="positions" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="positions">
					 <?php if(!$positions) {echo '<option value="">Current position</option>'; } ?>
					 <option value="all" <?php if ($positions === 'all') { echo 'selected="selected"'; } ?>> All </option>
					 <option value="cabinet" <?php if ($positions === 'cabinet') { echo 'selected="selected"'; } ?>> Cabinet </option>
					 <option value="shadow" <?php if ($positions === 'shadow') { echo 'selected="selected"'; } ?>> Shadow Cabinet </option>
				     <option value="government" <?php if ($positions === 'government') { echo 'selected="selected"'; } ?>> Government </option>
				     <option value="opposition" <?php if ($positions === 'opposition') { echo 'selected="selected"'; } ?>> Opposition </option>
				     <option value="none" <?php if ($positions === 'none') { echo 'selected="selected"'; } ?>> None </option>
				     </select>
				     </div>
					
					<!-- committees -->
					<div class="list-group-item">
					<select name="committee" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="committee">
						<option <?php if ($committee === 'all') { echo 'selected="selected"'; } ?> value="all">All</option>
					<?php 
					if ($committee == "") { echo '<option value="" selected="selected">Member of Committee</option>'; }
								
					foreach($Committees as $committeename) { 
					$isselectedcom = "";
					if ($committeename == $committee) { $isselectedcom = 'selected="selected"'; }
					?>
						
						<option value="<?= $committeename[0] ?>" <?php echo $isselectedcom ?>><?= $committeename[0] ?></option>
					<?php
					} ?>						
 
					</select>
					</div>
					
						<!-- departments -->
					<div class="list-group-item">
					<select name="department" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="department">
						<option <?php if ($department === 'all') { echo 'selected="selected"'; } ?> value="all">All</option>
					<?php 
					if ($department == "") { echo '<option value="" selected="selected">Department</option>'; }
								
					foreach($Departments as $departmentname) { 
					$isselecteddept = "";
					if ($departmentname[0] == $department) { $isselecteddept = 'selected="selected"'; }
					?>
						
					<option value="<?= $departmentname[0] ?>" <?php echo $isselecteddept ?>><?= $departmentname[0] ?></option>
					<?php
					} ?>						
 
					</select>
					</div>
					
				
					<!-- sortby -->
					 <div class="list-group-item">
					 <select name="sortby" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="sortby">
					 <?php if(!$sortby) {echo '<option value="">Sort by</option>'; } ?>
				     <option value="joined-recent" <?php if ($sortby === 'joined-recent') { echo 'selected="selected"'; } ?>> Date Joined (Most Recent) </option>
					 <option value="joined-furthest" <?php if ($sortby === 'joined-furthest') { echo 'selected="selected"'; } ?>> Date Joined (Least Recent) </option>
				     <option value="alpha" <?php if ($sortby === 'alpha') { echo 'selected="selected"'; } ?>> Alphabetical </option>
				     <option value="age" <?php if ($sortby === 'age') { echo 'selected="selected"'; } ?>> Age </option>
				     </select>
				     </div>
				     
						
					<!-- submit -->
					<div class="list-group-item">
						<button type="submit" id="mpsearch" class="btn btn-primary" onsubmit="SubmitForm(this);">Submit</button>
					</div>
			  </form>

            </div> <!--list group-->

        </div><!--panel-->

        </div><!--options column-->

        <!--list details column-->
        <div class="col-sm-9 bootcards-cards bootcards-who">
        
        <?php 
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
					  </div>		';
		} else {
		  $response=$hint;
		}
		
		//output the response
		echo $response;

	  ?>

		
		</div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

<?php include 'template/footer.php'; ?>

<?php include 'template/core.php'; ?>  
  
  </body>
</html>
