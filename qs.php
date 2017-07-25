<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Questions Picker</title>

  <?php include 'template/headinc.php';
  
$xmlDoc=new DOMDocument();

	//get parameters from URL
	$q=$_GET["q"];
	$date=$_GET["date"];
		if (!$date) {$date = date("Y-m-d");}
	$groupone=$_GET["groupone"];
	$grouptwo=$_GET["grouptwo"];
	$groupthree=$_GET["groupthree"];
	$groupfour=$_GET["groupfour"];
	
	$currentq=$_GET["quest"];
	$m=$_GET["m"];
		if(!$m) {$m = 8;}
	$house = "Commons";	

	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");

	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate=2017-07-18&_pageSize=500');
	$x=$xmlDoc->getElementsByTagName('item');
	$questionscount = $x->length;
		
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	$memberscount =  count($qxml);

	// Arry with party ID and party color (from BBC Elections coverage)	
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
	
	if ($questionscount == 1) {
			$hint = "";
	}
	else {	
		$hint="";
		for($i=0; $i<($x->length); $i++) {
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if ($QText[0]->textContent=="") {
			}
			else {
				$QuestionID=$x->item($i)->getElementsByTagName('ID');
				$MemberId=$x->item($i)->getElementsByTagName('tablingMemberPrinted');
					$CurrentQuestioner = trim($MemberId->item(0)->textContent);
				$Const=$x->item($i)->getElementsByTagName('constituency');
					$Constituency = trim($Const['prefLabel']->textContent);
				$TabledDate=$x->item($i)->getElementsByTagName('TabledDate');
				$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
				$DateDue=$x->item($i)->getElementsByTagName('AnswerDate');
				$BallotNo=$x->item($i)->getElementsByTagName('ballotNumber');
				$Department=$x->item($i)->getElementsByTagName('AnsweringBody');

				for ($y = 0; $y < $memberscount; $y++){
					$CurrentMP = trim($qxml->Member[$y]->ListAs);
						if($CurrentQuestioner === $CurrentMP) { 
							$DodsId=$qxml->Member[$y]->attributes()->Dods_Id;
							$MemberId=$qxml->Member[$y]->attributes()->Member_Id;
							$DisplayAs=$qxml->Member[$y]->DisplayAs;
							$party=$qxml->Member[$y]->Party;
							$PartyID =$qxml->Member[$y]->Party[0]->attributes()->Id;              	          	          	     
							$color = $colors[intval($PartyID)];
						}
				}
				
				$qarray[] = array('number'=>$BallotNo[0]->textContent,
								  'text'=>$QText[0]->textContent,
								  'type'=>$QuestionType[0]->textContent,
								  'member'=>$CurrentQuestioner,
								  'DodsId'=>$DodsId,
								  'MemberId'=>$MemberId,
								  'constituency'=>$Constituency,
								  'party'=>$party,
								  'color'=>$color);
			}
		}
		
		usort($qarray, function($a, $b) {
				return $a['number'] - $b['number'];
			});

		function comp($a, $b) {
			if ($a['type'] == $b['type']) {
				return $a['number'] - $b['number'];
			}
			return strcmp($a['type'], $b['type']);
		}

		usort($qarray, 'comp');
		$length = count($qarray);
		for($i=0; $i < $length; $i++) {
				if ($qarray[$i]["number"] == $q){
					$isselected = ' active';
				}
				else {
					$isselected = "";
				}
				if ($hint=="") {
					$hint='<a class="list-group-item'.$isselected.'" href="">
					   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
					   <h4 class="list-group-item-heading"> <span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' - '.$qarray[$i]["type"].' ('.$qarray[$i]["member"].') '.$qarray[$i]["constituency"].'</span></h4>
					   <p class="list-group-item-text">'.$qarray[$i]["text"].'</p></a>';
				} else {
					$hint=$hint .'<a class="list-group-item'.$isselected.'"  href="">
					   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
					   <h4 class="list-group-item-heading"><span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' - '.$qarray[$i]["type"].' ('. $qarray[$i]["member"].') '.$qarray[$i]["constituency"].'</span></h4>
					   <p class="list-group-item-text">'.$qarray[$i]["text"].'</p></a>';
				}
		}

	}	  

// Set output if no questions were found or to the correct values
if ($hint=="") {
  $response='<a class="list-group-item">
			 <h4 class ="list-group-item-heading">No questions tabled for '.$date.'</h4></a>';
} else {
// Otherwise respond with the information required 	
  $response=$hint;
}
		?>
	
	
</head>

<body>

<?php include 'template/navbar.php'; ?>

   <div class="container-fluid bootcards-container push-right">

    <div class="row">

      <!-- left list column -->
      <div class="col-sm-4 bootcards-list" id="list" data-title="Contacts">
        <div class="panel panel-default">       
          <div class="panel-body">
			<form id="mpsearch">
            <div class="search-form">
				<div class="form-group">	
				<label for="date-input" class="col-2 col-form-label">Select Questions Date</label>
				 <div class="col-10">
				<input id="date-input" type="date" value="<?php echo $date ?>" name="date" form="mpsearch">
				<button type="submit" form="mpsearch" class="btn btn-primary pull-right">Search</button>
				</div>
				</div>
            </div>
			</form>	
          </div><!--panel body-->

          <div class="list-group" id="livesearch"><!-- this was a live search originally -->
		
		<?php echo $response;   ?>
  		  
          </div><!--list-group-->

          <div class="panel-footer">
            <small class="pull-left">This section auto-populates by magic (and php).</small>
            <a class="btn btn-link btn-xs pull-right" href="http://data.parliament.uk/membersdataplatform/">
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
                <h3 class="panel-title pull-left">Question Details</h3>
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
