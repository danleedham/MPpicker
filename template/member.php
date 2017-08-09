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

	if(!$m) {$m=$_GET["m"];}
		if (!$m){ $m="8";}	
	if(!$house){$house=$_GET["house"];}
	if(!$house){$house = "Commons";}	
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
	if(!$photos){$photos=$_GET["photos"];}
	$betaimages =simplexml_load_file("http://leedhammedia.com/parliament/betaimages.xml") or die("Can't load Images");
	$imagescount = count($betaimages);

	
						$DodsId=$xml->Member[0]->attributes()->Dods_Id;
						if ($photos !== "screenshot") {	
							if ($house === "Commons") {
									for($ii=0; $ii < $imagescount; $ii++) {
										if (trim($betaimages->member[$ii]->KnownAs) == trim($xml->Member[0]->DisplayAs)){
											$BetaId = $betaimages->member[$ii]->imageid;
										}
									}
									$imageurl = 'https://api20170418155059.azure-api.net/photo/'.$BetaId.'.jpeg?crop=CU_1:1&width=500&quality=70';
									if (@getimagesize($imageurl)){}
									else {$imageurl = 'http://leedhammedia.com/parliament/images/thumbs'.$DodsId.'.jpg';}
									
									if (@getimagesize($imageurl)){}
									else {$imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$m;}
							}
							else { 
									$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
							}
						}
						else {
							$imageurl = 'http://leedhammedia.com/parliament/images/thumbs/'.$DodsId.'.jpg';
						}											
					?>
				
				<img src="<?php echo $imageurl; ?>" class="img-rounded pull-right main-member-image"">

				  <label>Name</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->DisplayAs ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Party</label>
                  <h4 class="list-group-item-heading" style="color:                  
                  <?php  $PartyID = $xml->Member[0]->Party[0]->attributes()->Id;              	          	     
                  	     $colors = array (
								"0"	  =>   "#000000",
								"4"	  =>   "#0087DC",
								"7"   =>   "#D46A4C",
								"8"   =>   "#DDDDDD",
								"15"  =>   "#DC241f",
								"17"  =>   "#FDBB30",
								"22"  =>   "#008142",
								"29"  =>   "#FFFF00",
								"30"  =>   "#008800",
								"31"  =>   "#99FF66",
								"35"  =>   "#70147A",
								"38"  =>   "#9999FF",
								"44"  =>   "#6AB023",
								"47"  =>   "#FFFFFF");
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
                  <label>Constituency</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->MemberFrom ?></h4>
                </div>
               
		<? // if the MP has twitter, show their latest tweets
		for($i = 0; $i < count($xml->Member[0]->Addresses[0]); $i ++) {
			if ($xml->Member[0]->Addresses->Address[$i]->Type == "Twitter") {
				$twitter = $xml->Member[0]->Addresses->Address[$i]->Address1[0]; 
				echo '<div class="list-group-item">
				<a class="twitter-timeline" href="'.$twitter.'" data-chrome="nofooter noheader noborders"  data-tweet-limit="2">Tweets</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script></div> ';
			}
		} ?>
		
	<div class="panel-footer">
                  <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
                </div>
              </div>
              </div>	