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
		if(!$q) {$q = 1;}
	$date=$_GET["date"];
		if (!$date) {$date = date("Y-m-d");}
	$groups=$_GET["groups"];

	$m=$_GET["m"];
	$house = "Commons";
	$photos=$_GET["photos"];

	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&_pageSize=500');
	$x=$xmlDoc->getElementsByTagName('item');
	$questionscount = $x->length;
		
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	$memberscount =  count($qxml);
	
	$betaimages =simplexml_load_file("http://leedhammedia.com/parliament/test/betaimages.xml") or die("Can't load Beta Images");
	$imagescount =  count($betaimages);

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
								  'DisplayAs'=>$DisplayAs,
								  'DodsId'=>$DodsId,
								  'MemberId'=>$MemberId,
								  'constituency'=>$Constituency,
								  'party'=>$party,
								  'color'=>$color);
			}
		}
		
		function comp($a, $b) {
			if ($a['type'] == $b['type']) {
				return $a['number'] - $b['number'];
			}
			return strcmp($a['type'], $b['type']);
		}
		$length = count($qarray);
		
		if ($length !== 0) {
			usort($qarray, 'comp');
			
			for($i=0; $i < $length; $i++) {
					
					
					if ($qarray[$i]["number"] == $q){
						$isselected = ' active';
					}
					else {
						$isselected = "";
					}
					if ($hint=="") {
						$hint='<a class="list-group-item'.$isselected.'" href="?date='.$date.'&q='.$qarray[$i]["number"].'">
						   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
						   <h4 class="list-group-item-heading"> <span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' '.$qarray[$i]["DisplayAs"].'</h4>
						   <p class="list-group-item-text">'.$qarray[$i]["constituency"].'</p></a>';
					} else {
						$hint=$hint .'<a class="list-group-item'.$isselected.'"  href="?date='.$date.'&q='.$qarray[$i]["number"].'">
						   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
						   <h4 class="list-group-item-heading"><span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' '. $qarray[$i]["DisplayAs"].'</span></h4>
						   <p class="list-group-item-text">'.$qarray[$i]["constituency"].'</p></a>';
					}
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

//Let's catch some information for below
	if(!$m) { 
		if ($hint !== "") {
			$m = $qarray[$q - 1]["MemberId"];
		}
		else {
			$m = 4516;
		}
	}
	
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
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
					<div class="col-2">
					<input id="date-input" type="date" value="<?php echo $date ?>" name="date" form="mpsearch">
					<input id="choosephotos" class="pull-right" <?php if ($photos == "screenshot") {echo "checked";} ?> type="checkbox" value="screenshot" name="photos" form="mpsearch" data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-off="Stock">
					<input id="q" type="hidden" value="<?php echo $q ?>" name="q" form="mpsearch">
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
                <h3 class="panel-title pull-left">Question <?php echo $q ?> Details</h3>
                <a class="btn btn-primary pull-right" onclick="location.href='?date=<?php echo $date;?>&q=<?php echo intval($q + 1);?>';" data-toggle="modal" data-target="#editModal">
                  <i class="fa fa-arrow-right"></i><span href="?date=<?php echo $date;?>&q=<?php echo intval($q - 1);?>">Next</span>
                </a>
				 <a class="btn btn-primary pull-right" onclick="location.href='?date=<?php echo $date;?>&q=<?php echo intval($q - 1);?>';" data-toggle="modal" data-target="#editModal">
                  <i class="fa fa-arrow-left"></i><span>Previous</span>
                </a>
              </div>
              <div class="list-group">
 				<div class="list-group-item">  
                  <h4 class="list-group-item-heading">
				  <?php echo $xml->Member[0]->DisplayAs ?>
				  <span style="color:                  
                  <?php  $PartyID = $xml->Member[0]->Party[0]->attributes()->Id;              	          	     
	  					 echo $colors[intval($PartyID)];
					?>">
				  <?php echo $xml->Member[0]->Party ?></span>
				  </h4>
                </div>
				<div class="list-group-item">
				<img src="
					<?php 
						$DodsId=$xml->Member[0]->attributes()->Dods_Id;
						if ($photos !== "screenshot") {
							
							if ($house === "Commons") {
									for($ii=0; $ii < $imagescount; $ii++) {
										if (trim($betaimages->member[$ii]->KnownAs) == trim($xml->Member[0]->DisplayAs)){
											$BetaId = $betaimages->member[$ii]->imageid;
										}
									}
									echo 'https://api20170418155059.azure-api.net/photo/'.$BetaId.'.jpeg?crop=MCU_3:2&width=1000&quality=80';
							}
							else { 
									echo 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
							}
						}
						else {
							echo 'http://leedhammedia.com/parliament/test/images/'.$DodsId.'.jpg';
						}								
						
					?>" class="img-rounded main-question-image">
				</div>	
                <div class="list-group-item">
                  <label>Constituency</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->MemberFrom ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Question</label>
                  <h4 class="list-group-item-heading"><?php echo $qarray[$q - 1]["text"]; ?></h4>
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
                
                 <div class="panel-footer">
                  <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
                </div>
              </div>
              </div>

            </div><!--contact card-->

          <!--contact details -->
          <div id="contactCard">

            <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Group Details</h3>
              </div>
              <div class="list-group">
                <div class="list-group-item">
					<form id="groups">
						<div class="search-form">
							<div class="form-group">	
							<label for="date-input" class="col-2 col-form-label">Enter groups on seperate lines with questions space delimited</label>
								<div class="col-10">
									 <textarea class="form-control" rows="5" id="groups" form="groups"></textarea>
									 <button type="submit" form="groups" class="btn btn-primary pull-left">Set groups</button>
									 <br />
								</div>
							</div>
						</div>
					</form>	
                </div>
              
                 <div class="panel-footer">
                  <small>Please enter the question groups on seperate lines using commas to split each question in the group</small>
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
