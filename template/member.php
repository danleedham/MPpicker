<div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Member Details</h3>
                <div class="btn-group pull-right visible-xs">
                  <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#editModal">
                    <i class="fa fa-pencil"></i><span>Edit</span>
                  </a>
                </div>  
                <a class="btn btn-primary pull-right hidden-xs" href="#" data-toggle="modal" data-target="#editModal">
                  <i class="fa fa-pencil"></i><span>Edit</span>
                </a>
              </div>
              <div class="list-group">
                <div class="list-group-item">
                
                <?php
	// If $m isn't already set in a require, get it from URL if it's passed
	if(!isset($m) && isset($_GET['m'])) {$m=$_GET["m"];}
	// If $m is not passed, set it as the Prime Minister (Hard-coded value)
	if(!isset($m)){$m="8";}	
	
	// If $house isn't already set in a require, get it from URL if it's passed
	if(!isset($house) && isset($_GET['house'])){$house=$_GET["house"];}
	
	// Load selected Member ($m) full biography information
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
	
	// If $photos isn't already set in a require, get it from URL if it's passed
	if(!isset($photos) && isset($_GET['photos'])){$photos=$_GET["photos"];}
	
	// If beta images are loaded prior to this then skip
	if(!isset($feed)){
		$feed = file_get_contents("../betaimages.xml");
		$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
		$imagescount = count($betaimages);
	}

	if(!isset($house)){ $house = $xml->Member[0]->House; }
						$DodsId=$xml->Member[0]->attributes()->Dods_Id;
						if (!isset($photos) or $photos  !== "screenshot") {	
							if ($house == "Commons") {
									for($ii=0; $ii < $imagescount; $ii++) {
										if (trim($betaimages->member[$ii]->KnownAs) == trim($xml->Member[0]->DisplayAs)){
											$BetaId = $betaimages->member[$ii]->imageid;
										}
									}
									$imageurl = 'images/stock/500/'.$BetaId.'.jpeg';
									if (isset($BetaId) && $BetaId == ""){
										$imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$m;
									}
									
							} else { 
									$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
							}
						} else {
							$imageurl = 'images/screenshot/thumbs/'.$DodsId.'.jpg';
						}											
					?>
				
				<img src="<?php echo $imageurl; ?>" class="img-rounded pull-right main-member-image"">

				  <label>Name</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->DisplayAs ?></h4>
                  <span stlye="padding-top: 4px !important;"><?php echo $xml->Member[0]->BasicDetails[0]->GivenForename ?> <?php echo $xml->Member[0]->BasicDetails[0]->GivenMiddleNames ?> <?php echo $xml->Member[0]->BasicDetails[0]->GivenSurname ?></span>
                </div>    

                <div class="list-group-item">
                  <label>Party</label>
                  <h4 class="list-group-item-heading" style="color:                  
                  <?php  $PartyID = $xml->Member[0]->Party[0]->attributes()->Id;              	          	     
                  	     	require_once('colors.php');
	  					 	echo $colors[intval($PartyID)];
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
					echo '<h4 class="list-group-item-heading">'.$Committee . "</h4>";
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
							echo '<li>'.$Committee . " (".date("M Y",strtotime($xml->Member[0]->Committees->Committee[$i]->StartDate[0]))." - ".date("M Y",strtotime($xml->Member[0]->Committees->Committee[$i]->EndDate[0])).")</li>";
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
        	<script>      
			function twitter(handle){
				var twitter = document.getElementById("twitter");
				twitter.style.display = twitter.style.display === 'none' ? '' : 'none';
			   $("#twitter").load('template/twitter.php?handle='+handle);
			}
			function loadextras(){
				var yourUl = document.getElementById("extras");
				yourUl.style.display = yourUl.style.display === 'none' ? '' : 'none';
			}
			</script>
