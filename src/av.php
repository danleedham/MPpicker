<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Parliamentary Stacker</title>

  <?php include 'template/headinc.php';
  		
	// If the user wants to use this in the Lords, they just have to call. The query shall do the rest
	if(isset($_GET["house"])){
		$house=$_GET["house"];
		if ($house == "Lords") {
			$location = "Lords+Chamber";
		} else {
			$location = "Commons+Chamber";
			}
	
	} else {
		$location = "Commons+Chamber";
	}	
	
	// Load the most recent House of Commons Chamber TV programs list
	$programlist=simplexml_load_file("http://lda.data.parliament.uk/tvprogrammes.xml?location=".$location."&exists-location=true&_view=avLiveLoggingProgrammeListViewer&_pageSize=1&_page=0");

	// Extract today's ID
	$todaysid = str_replace("http://data.parliament.uk/resources/","",$programlist->items->item[0]['href']);

	// Load today's AV logging
	$todaysurl = "http://lda.data.parliament.uk/tvprogrammes/".$todaysid.".xml";
	$todayscommons = simplexml_load_file($todaysurl);

	$latesturl = 'http://lda.data.parliament.uk/resources/'.$todaysid.'/videoclips/1.xml';
	$latestclip = simplexml_load_file($latesturl);

	// print_r($latestclip);
	$m = str_replace("http://data.parliament.uk/members/","",$latestclip->primaryTopic[0]->member->item[0]['href']);
	$imageurl = str_replace("width=480","width=1920",$latestclip->primaryTopic[0]->thumbnailUrl[0]['href']);
	$startDate = $latestclip->primaryTopic[0]->startDate;
	$time = strtotime($startDate.' UTC');
	$dateInLocal = date("Y-m-d H:i:s", $time);

	// Get the list of new member images until their new API is working
	$feed = file_get_contents("betaimages.xml");
	$betaimages = simplexml_load_string($feed);
	$imagescount =  count($betaimages);


	// If $m isn't already set in a require, get it from URL if it's passed
	if(!isset($m) && isset($_GET['m'])) {$m=$_GET["m"];}
	// If $m is not passed, set it as the Prime Minister (Hard-coded value)
	if(!isset($m)){$m="8";}	

	// If $house isn't already set in a require, get it from URL if it's passed
	if(!isset($house) && isset($_GET['house'])){$house=$_GET["house"];}

	
	// Load selected Member ($m) full biography information
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
			
	$PartyID = $xml->Member[0]->Party[0]->attributes()->Id;              	          	     
						require_once('template/colors.php');	
	    
?>
						
</head>

<body>

   <div class="container-fluid bootcards-container push-right">

    <div class="row">

        <!--list details column-->
        <div class="col-sm-12 bootcards-cards">

          <!--contact details -->
          <div id="contactCard">

<?php echo '<!-- URL: http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id='.$m.'/FullBiog -->'; ?>
					
			<div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left"><span class="partybox" style="background:<?php echo $colors[intval($PartyID)]; ?>"></span><?php echo $xml->Member[0]->DisplayAs ?></h3>
              </div>
              <div class="list-group">
                <div class="list-group-item">					
				
				<img src="<?php echo $imageurl; ?>" class="img-rounded pull-right main-member-image"">

				  <label>Name</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->DisplayAs ?></h4>
                  <span stlye="padding-top: 4px !important;"><?php echo $xml->Member[0]->BasicDetails[0]->GivenForename ?> <?php echo $xml->Member[0]->BasicDetails[0]->GivenMiddleNames ?> <?php echo $xml->Member[0]->BasicDetails[0]->GivenSurname ?></span>
                </div>    

                <div class="list-group-item">
                  <label>Party</label>
                  <h4 class="list-group-item-heading" style="color:                  
                  <?php echo $colors[intval($PartyID)];
					?>"><?php echo $xml->Member[0]->Party ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Constituency</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->MemberFrom ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Start Date in the House</label>
                  <h4 class="list-group-item-heading"><?php echo date('d-m-Y', strtotime($xml->Member[0]->HouseStartDate)) ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Current Notable Positions</label>
			<?php for($i = 0; $i < count($xml->Member[0]->GovernmentPosts[0]); $i ++) {
			if (!strtotime($xml->Member[0]->GovernmentPosts->GovernmentPost[$i]->EndDate[0]) >= time()) {
				$Government = $xml->Member[0]->GovernmentPosts->GovernmentPost[$i]->HansardName[0]; 
				echo '<h4 class="list-group-item-heading">'. $Government . "</h4>";
				}
			} 			
			// let's see if they're currently a member of the opposition
			for($i = 0; $i < count($xml->Member[0]->OppositionPosts[0]); $i ++) {
				if (!strtotime($xml->Member[0]->OppositionPosts->OppositionPost[$i]->EndDate[0]) >= time()) {
					$Opposition = $xml->Member[0]->OppositionPosts->OppositionPost[$i]->HansardName[0]; 
					echo '<h4 class="list-group-item-heading">'. $Opposition . "</h4>";
				}
			}
			// let's see if they've got a parliamentary post
			for($i = 0; $i < count($xml->Member[0]->ParliamentaryPosts[0]); $i ++) {
				if (!strtotime($xml->Member[0]->ParliamentaryPosts->ParliamentaryPost[$i]->EndDate[0]) >= time()) {
					$Parliamentary = $xml->Member[0]->ParliamentaryPosts->ParliamentaryPost[$i]->Name[0]; 
					 echo '<h4 class="list-group-item-heading">'. $Parliamentary . "</h4>";
				}
			}
			// let's see if they're currently on any committees
			for($i = 0; $i < count($xml->Member[0]->Committees[0]); $i ++) {
				if (!strtotime($xml->Member[0]->Committees->Committee[$i]->EndDate[0]) >= time()) {
					$Committee = $xml->Member[0]->Committees->Committee[$i]->Name[0];
					$ischair = "";
					if(isset($xml->Member[0]->Committees->Committee[$i]->ChairDates->ChairDate[0]->StartDate[0])) {
						$ischair = " (Chair)";
					} 
					echo '<h4 class="list-group-item-heading">'.$Committee.$ischair."</h4>";
				}
			} 
			
			?>

                </div>
              <div class="list-group-item">
                  
				<?php for($i = 0; $i < count($xml->Member[0]->Addresses[0]); $i ++) {
						if ($xml->Member[0]->Addresses->Address[$i]->Type == "Twitter") {
							$twitter = $xml->Member[0]->Addresses->Address[$i]->Address1[0];  
							}
					}
					?>
				 <a href="#" onclick="loadextras();return false;" class="btn btn-danger" role="button">Late shift data</a> 
				 <?php if(isset($twitter)): ?> 
				 <a href="#" onclick="twitter('<?php echo $twitter ?>');return false;" class="btn btn-info" role="button">Toggle Twitter</a> 
              	 <?php endif; ?> 
              	 </div>
              	 <div class="list-group-item" id="twitter" style="display:none;"></div>
                <div class="list-group-item" id="extras" style="display:none;">
                <?php if(isset($xml->Member[0]->BiographyEntries[0]->BiographyEntry[0]->Entry)): ?>
                 <h4>Interests</h4>
                  <p><?php echo $xml->Member[0]->BiographyEntries[0]->BiographyEntry[0]->Entry ?></p>
                <?php endif; ?>
                <?php if(isset($xml->Member[0]->Interests[0]->Category[$i]->Interest[0]->RegisteredInterest)){
                		$RegInt = trim($xml->Member[0]->Interests[0]->Category[$i]->Interest[0]->RegisteredInterest);
                		}
                	  if (isset($RegInt)): ?>
                 <h4>Business Interests</h4>
                 <ul>
                <?php   
				 	for($i = 0; $i < count($xml->Member[0]->Interests[0]); $i ++) {
				 		if ($xml->Member[0]->Interests[0]->Category[$i]->Interest[0]->RegisteredInterest) {
							$interest = $xml->Member[0]->Interests[0]->Category[$i]->Interest[0]->RegisteredInterest; 
							echo '<li>'.$interest."</li>";
					    }
					}
				endif; ?>
				</ul>
				<?php 
					if(isset($xml->Member[0]->BasicDetails[0]->TownOfBirth)){
						$TownOfBirth = trim($xml->Member[0]->BasicDetails[0]->TownOfBirth);
					}
					if(isset($TownOfBirth)): ?>
                 <h4>Born In</h4>
                 <?php echo trim($xml->Member[0]->BasicDetails[0]->TownOfBirth).', '.trim($xml->Member[0]->BasicDetails[0]->CountryOfBirth); ?>
                 <?php endif; ?>
                 
                 <?php 
					if(isset($xml->Member[0]->BasicDetails[0]->TownOfBirth)){
						$TownOfBirth = trim($xml->Member[0]->BasicDetails[0]->TownOfBirth);
					}
					if(isset($TownOfBirth)): ?>
						
                 <h4>Date of Birth</h4>
                 <?php
                 	$dob = $xml->Member[0]->DateOfBirth;
                 	$today = new DateTime();
                 	$DOB = new DateTime($xml->Member[0]->DateOfBirth);
                 	$diff = date_diff($DOB,$today);
                 	echo date('d-m-Y',strtotime($xml->Member[0]->DateOfBirth)).' ('.$diff->y.')'; ?>
                 <?php endif; ?>
                 
                 <?php if(isset($xml->Member[0]->Constituencies->Constituency[0])): ?>
                 <h4>Constituencies Represented</h4>
                 <ul>
				 <?php   
				 	for($i = 0; $i < count($xml->Member[0]->Constituencies[0]); $i ++) {
				 		if ($xml->Member[0]->Constituencies->Constituency[$i]->Name[0]) {
							$Constituency = $xml->Member[0]->Constituencies->Constituency[$i]->Name[0]; 
							echo '<li>'.$Constituency . " (".$xml->Member[0]->Constituencies->Constituency[$i]->Election[0]->Name.")</li>";
					    }
					}
				 ?>
				 </ul>
				 <?php endif; ?>
				 
				 <?php if(isset($xml->Member[0]->ElectionsContested->ElectionContested[0]->Constituency[0])): ?>
                 <h4>Constituencies Contested Not Won</h4>
                 <ul>
				 <?php   
				 	for($i = 0; $i < count($xml->Member[0]->ElectionsContested[0]); $i ++) {
				 		if ($xml->Member[0]->ElectionsContested->ElectionContested[$i]->Constituency[0]) {
							$Committee = $xml->Member[0]->ElectionsContested->ElectionContested[$i]->Constituency[0]; 
							echo '<li>'.$Committee . " (".$xml->Member[0]->ElectionsContested->ElectionContested[$i]->Election[0]->Name.")</li>";
					    }
					}
				 ?>
				 </ul>
				 <?php endif; ?>
                 <h4>Previous Positions</h4>
                 <ul>
				 <?php   
				 	for($i = 0; $i < count($xml->Member[0]->GovernmentPosts[0]); $i ++) {
				 		if ($xml->Member[0]->GovernmentPosts->GovernmentPost[$i]->EndDate[0]) {
							$Committee = $xml->Member[0]->GovernmentPosts->GovernmentPost[$i]->Name[0]; 
							echo '<li>'.$Committee . " (".date("M Y",strtotime($xml->Member[0]->GovernmentPosts->GovernmentPost[$i]->StartDate[0]))." - ".date("M Y",strtotime($xml->Member[0]->GovernmentPosts->GovernmentPost[$i]->EndDate[0])).")</li>";
					    }
					}
					for($i = 0; $i < count($xml->Member[0]->OppositionPosts[0]); $i ++) {
				 		if ($xml->Member[0]->OppositionPosts->OppositionPost[$i]->EndDate[0]) {
							$Committee = $xml->Member[0]->OppositionPosts->OppositionPost[$i]->Name[0]; 
							echo '<li>'.$Committee . " (".date("M Y",strtotime($xml->Member[0]->OppositionPosts->OppositionPost[$i]->StartDate[0]))." - ".date("M Y",strtotime($xml->Member[0]->OppositionPosts->OppositionPost[$i]->EndDate[0])).")</li>";
					    }
					} 	 			
					for($i = 0; $i < count($xml->Member[0]->Committees[0]); $i ++) {
						if ($xml->Member[0]->Committees->Committee[$i]->EndDate[0]) {
							$Committee = $xml->Member[0]->Committees->Committee[$i]->Name[0];
							$ischair = ""; 
							if ($xml->Member[0]->Committees->Committee[$i]->ChairDates->ChairDate[0]) {
								$ischair = "Chair - ";
							}
							echo '<li>'.$ischair.$Committee . " (".date("M Y",strtotime($xml->Member[0]->Committees->Committee[$i]->StartDate[0]))." - ".date("M Y",strtotime($xml->Member[0]->Committees->Committee[$i]->EndDate[0])).")</li>";
						}
					} 	
					for($i = 0; $i < count($xml->Member[0]->ParliamentaryPosts[0]); $i ++) {
						if ($xml->Member[0]->ParliamentaryPosts->ParliamentaryPost[$i]->EndDate[0]) {
							$Committee = $xml->Member[0]->ParliamentaryPosts->ParliamentaryPost[$i]->Name[0]; 
							echo '<li>'.$Committee . " (".date("M Y",strtotime($xml->Member[0]->ParliamentaryPosts->ParliamentaryPost[$i]->StartDate[0]))." - ".date("M Y",strtotime($xml->Member[0]->ParliamentaryPosts->ParliamentaryPost[$i]->EndDate[0])).")</li>";
						}
					}           
				?> </ul>
					</div>
				</div>
				
				<div class="panel-footer">
                  <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
                </div>
              </div>
              </div>	
            </div><!--contact card-->

        </div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
