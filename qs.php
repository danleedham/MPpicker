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
		if(!$q) {$q = 0;}
	$qtype=$_GET["type"];
		if(!$qtype) { $qtype = "Substantive"; }
	$qdept=$_GET["dept"]; 
		
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
	
	$betaimages =simplexml_load_file("data/betaimages.xml") or die("Can't load Beta Images");
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
				$Dept=$x->item($i)->getElementsByTagName('AnsweringBody');
					$Department=trim($Dept->item(0)->textContent);

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
								  'dept'=>$Department,
								  'text'=>$QText[0]->textContent,
								  'type'=>$QuestionType[0]->textContent,
								  'member'=>$CurrentQuestioner,
								  'DisplayAs'=>$DisplayAs,
								  'DodsId'=>$DodsId,
								  'MemberId'=>$MemberId,
								  'constituency'=>$Constituency,
								  'party'=>$party,
								  'color'=>$color);
								  
				$deptarray[] = array('dept' => $Department);
				$typearray[] = array('type' => $QuestionType[0]->textContent);
	  			
			}
		}
		// This gives us a unique list of departments & question types
		if (count($deptarray) !== 0) {
			$deptarray = array_map("unserialize", array_unique(array_map("serialize", $deptarray)));
		}
		if (count($typearray) !== 0) {
			$typearray = array_map("unserialize", array_unique(array_map("serialize", $typearray)));
		}
		// Count how many unique departments/types there are
		$deptscount = count($deptarray);
		$typecount = count($typearray);
		
		// Function to sort questions by type then by number
		function comp($a, $b) {
			if ($a['type'] == $b['type']) {
				return $a['number'] - $b['number'];
			}
			return strcmp($a['type'], $b['type']);
		}
		// Count how many questions there are
		$length = count($qarray);
		
	// If there are questions, sort the questions & generate list
	if ($length !== 0) {
			usort($qarray, 'comp');
			
		// Generate the list of questions 	
		for($i=0; $i < $length; $i++) {
			
			// Stop trying to load a question we can't
			
			if($i == $q){
				
				if($qtype ==  $qarray[$i]["type"]) {
					$isselected = ' active';
					$currenti = $i;
				}
				else {
					$isselected = "";
				}
			}
			
			else {
					$isselected = "";
			}
					
			if ($qarray[$i]["type"] == "Topical") {
						$istopical = '&type=Topical';
			}
			else {
						$istopical = "";
			}
							
			// If no department is set, let's use the one from q		
			if (!$qdept or count($deptarray) === 1) { $qdept = $qarray[0]["dept"]; }
					
			if ($qarray[$i]["dept"] !== $qdept && intval($deptscount) > 1) {
			}
			else {	
				if ($qarray[$i]["type"] == $qtype) {		
					if ($hint=="") {
						$hint='<a class="list-group-item'.$isselected.'" href="?date='.$date.'&q='.$i.$istopical.'">
						   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
						   <h4 class="list-group-item-heading"> <span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' '.$qarray[$i]["DisplayAs"].'</h4>
						   <p class="list-group-item-text">'.$qarray[$i]["constituency"].'</p></a>';
					} 
					else {
						$hint=$hint .'<a class="list-group-item'.$isselected.'"  href="?date='.$date.'&q='.$i.$istopical.'">
						   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
						   <h4 class="list-group-item-heading"><span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' '. $qarray[$i]["DisplayAs"].'</span></h4>
						   <p class="list-group-item-text">'.$qarray[$i]["constituency"].'</p></a>';
					}
				}
			  }
			}
		}
	}	  

// Set output if no questions were found or to the correct values
if ($hint=="") {
  
  if(!$_GET["type"]){ 
  		$iftype = '';
  }
  else {
  		$iftype = ' '.$qtype;
  }
  
  if(!$qdept){$ifdept = '';}
  else {$ifdept = ' to '.$qdept;}
  $response='<a class="list-group-item">
			 <h4 class ="list-group-item-heading">No'.$iftype.' questions on '.$date.$ifdept.'</h4></a>';
} else {
// Otherwise respond with the information required 	
  $response=$hint;
}

//Let's catch some information for below
	if(!$m) { 
		if ($hint !== "") {
			$m = $qarray[$currenti]["MemberId"];
		}
		else {
			$m = 4516;
		}
	}
// Now load the data for the currently selected member. This shall be replaced by AJAX on selection futher down	
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
?>
	
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
				<div class="form-inline">
					<input id="date-input" type="date" class="input-sm form-control" value="<?php echo $date ?>" name="date" form="mpsearch">
					<input id="choosephotos" style="float:right !important;" class="pull-right" <?php if ($photos == "screenshot") {echo "checked";} ?> type="checkbox" value="screenshot" name="photos" form="mpsearch" data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-off="Stock">
					<button type="submit" form="mpsearch" class="btn btn-primary pull-right">Load</button>
				</div>
				<?php //If there are multiple departments let the user select which one to pull questions from
					if(intval($deptscount) > 1) : 
				?>
				<div class="form-inline" style="padding-top:6px !important;">
				<select id="dept" name="dept" class="form-control" form="mpsearch">
						<?php 
							foreach ($deptarray as $key => $value) {
								if ($qdept == $value["dept"]) { $isdept = ' selected="selected" ';}
								else { $isdept = "";}
							   echo '<option'.$isdept.' value="'. $value["dept"].'">'. $value["dept"].'</option>';
							}
						?>
				</select>
				</div>
				<?php endif;
				 //If there are multiple departments let the user select which one to pull questions from
					if(intval($typecount) > 1) : 
				?>
				<div class="form-inline" style="padding-top:6px !important;">
				<select id="type" name="type" class="form-control" form="mpsearch">
						<?php 
							foreach ($typearray as $key => $value) {
								if ($qtype == $value["type"]) { $istype = ' selected="selected" ';}
								else { $istype = "";}
							   echo '<option'.$istype.' value="'. $value["type"].'">'. $value["type"].'</option>';
							}
						?>
				</select>
				</div>
				<?php endif; ?>
				
            </div>
			</form>	
          </div><!--panel body-->
          <div class="list-group" id="livesearch">
          
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
                <h3 class="panel-title pull-left"><?php echo $qarray[$currenti]["type"] ?> Question <?php echo $qarray[$currenti]["number"] ?> Details</h3>
                <a class="btn btn-primary pull-right" onclick="location.href='?date=<?php echo $date;?>&q=<?php echo intval($q + 1);?>&dept=<?php echo urlencode($qdept) ?>';" data-toggle="modal" data-target="#editModal">
                  <i class="fa fa-arrow-right"></i><span href="?date=<?php echo $date;?>&q=<?php echo intval($q - 1);?>&dept=<?php echo urlencode($qdept) ?>">Next</span>
                </a>
				 <a class="btn btn-primary pull-right" onclick="location.href='?date=<?php echo $date;?>&q=<?php echo intval($q - 1); if($qtype=="Topical") {echo '&type=Topical';} ?>';" data-toggle="modal" data-target="#editModal">
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
							echo 'images/'.$DodsId.'.jpg';
						}								
						
					?>" class="img-rounded main-question-image">
				</div>	
                <div class="list-group-item">
                  <label>Constituency</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->MemberFrom ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Question</label>
                  <h4 class="list-group-item-heading"><?php echo $qarray[$currenti]["text"]; ?></h4>
                </div>

                <div class="list-group-item">
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
