<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Parliamentary Stacker</title>

  <?php include 'template/headinc.php'; ?>
		<?php

		//get the q parameter from URL
		$m=$_GET["m"];
		$q=$_GET["q"];
		if (!$q){ $SearchContactsMessage="Start typing..."; 
				}
			else { $SearchContactsMessage=$q; 
				}
		$searchby=$_GET["searchby"];
			if(!$searchby) {$searchby="name";}
		$house=$_GET["house"];
		if (!$house) {$house = "Commons";}
		// Bad fix for if the house of lords is chosen but no member is called
		if ($house == "Lords" && !$m) { $m = "4329";
		  }	
		if (!$m){ $m="8";}	
		$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
		
		?>

<!-- Here's the script that *should* get the relevant members from the search. Note search string must be greater than 2 -->		
		<script>
			function showResult(str) {
			
			  if (!document.getElementById("choosehouse").checked){
				 var house = "Commons";
				}
				 else {
				 var house = "Lords";
				 }
			  if (!document.getElementById("searchby").checked){
				 var searchby = "name";
				}
				 else {
				 var searchby = "constituency";
				 } 
			  if (str.length<=2) { 
				document.getElementById("livesearch").innerHTML="";
				document.getElementById("livesearch").style.border="0px";
				return;
			  }
			  if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			  } else {  // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			  xmlhttp.onreadystatechange=function() {
				if (this.readyState==4 && this.status==200) {
				  document.getElementById("livesearch").innerHTML=this.responseText;
				}
			  }
			  xmlhttp.open("GET","template/livesearch.php?house="+house+"&searchby="+searchby+"&q="+str,true);
			  xmlhttp.send();
			}
			</script>
						
	
</head>

<body>

   <div class="container-fluid bootcards-container push-right">

    <div class="row">

      <!-- left list column -->
      <div class="col-sm-4 bootcards-list" id="list" data-title="Contacts">
        <div class="panel panel-default">       
          <div class="panel-body">
			<form id="mpsearch">
            <div class="search-form">
				<div class="form-group">
				  <input type="text" name="q" form="mpsearch" class="form-control" placeholder="<?php echo $SearchContactsMessage ;?>" size="10" onkeyup="showResult(this.value)">			
				</div>
				<div class="form-group">	
				<input id="choosehouse" <?php if ($house == "Lords") {echo "checked";} ?> type="checkbox" value="Lords" name="house" form="mpsearch" data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="Lords" data-off="Commons">
				<input id="searchby" <?php if ($searchby == "constituency") {echo "checked";} ?> type="checkbox" value="constituency" name="searchby" form="mpsearch" data-toggle="toggle" data-on="Const" data-off="Name">
				<input id="photos" <?php if ($photos == "screenshot") {echo "checked";} ?> type="checkbox" value="screenshot" name="photos" form="mpsearch" data-toggle="toggle" data-onstyle="warning" data-on="ScreenShot" data-off="Stock">
				<button type="submit" form="mpsearch" class="btn btn-primary pull-right">Search</button>
				</div>
            </div>
			</form>	
          </div><!--panel body-->

          <div class="list-group" id="livesearch">
		
		<?php 
         	if (!$q){
				echo file_get_contents("http://leedhammedia.com/parliament/template/initiallist.php?m=".$m."&house=".$house); 
            }
            else {
				$mselected = '&mselected='.$m;
				echo file_get_contents("http://leedhammedia.com/parliament/template/livesearch.php?q=".$q."&house=".$house."&searchby=".$searchby.$mselected.'"'); 
			} 
          ?>  
          </div><!--list-group-->

          <div class="panel-footer">
            <small class="pull-left">This section auto-populates by magic (and php).</small>
            <a class="btn btn-link btn-xs pull-right" href="data.parliament.uk/membersdataplatform/">
              Live Data</a>
          </div>
        </div><!--panel-->

        </div><!--list-->

        <!--list details column-->
        <div class="col-sm-8 bootcards-cards">

          <!--contact details -->
          <div id="contactCard">

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
				<?php if ($house === "Commons") {
					
					echo '<img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$m.'" class="img-rounded pull-right main-member-image">';
							}
					  else { $DodsId=$xml->Member[0]->attributes()->Dods_Id; 
							 echo '<img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg" class="img-rounded pull-right main-member-image">';
						}
				  
				  ?>
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

            </div><!--contact card-->

        </div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
