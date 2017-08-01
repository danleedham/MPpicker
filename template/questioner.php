<?php 
$xmlDoc=new DOMDocument();

	//get parameters from URL
	if(!$uin) {$uin=$_GET["uin"];}
	$next=$_GET["next"];
	$prev=$_GET["prev"];
	
	if(!$date) {$date=$_GET["date"];}

	$house = "Commons";
	if(!$photos){$photos=$_GET["photos"];}

	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&uin='.$uin);
	$x=$xmlDoc->getElementsByTagName('item');
	$questionscount = $x->length;
		
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	$memberscount =  count($qxml);
	
	$betaimages =simplexml_load_file("http://leedhammedia.com/parliament/betaimages.xml") or die("Can't load Beta Images");
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
			$m = 4516;
	}
	else {	
		$hint="";
		for($i=0; $i<($x->length); $i++) {
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if ($QText[0]->textContent=="") {
			}
			else {
				$QuestionID=$x->item($i)->getElementsByTagName('ID');
				$AnswerDate=$x->item($i)->getElementsByTagName('AnswerDate');
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
								  'date'=>$AnswerDate[0]->textContent,
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
			}
		}
	// Function to sort questions by date
	function compsort( $a, $b ) {
		return strtotime($b["date"]) - strtotime($a["date"]);
	}
	// Count how many questions there are
	$length = count($qarray);
		
	// If there are questions, sort the questions
	if ($length !== 0) {
			usort($qarray, 'compsort');
		}
	}	  

	// If date is set, use it to ensure we get the correct question
	for ($i = 0; $i < count($qarray); $i++){
			if($qarray[$i]["date"] == $date){
				$q = $i;
			}
		}
	// If date isn't set, let's presume they want the most recent question	
	if(!$q) { $q = 0; }			
	$m = intval($qarray[$q]["MemberId"]);

	// Now load the data for the currently selected member. This shall be replaced by AJAX on selection futher down	
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
?>

           <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">
              	<?php //If there are multiple departments let the user select which one to pull questions from
					if($uin) : 
				?>
           <?php echo $qarray[$q]["type"] ?> Question <?php echo $qarray[$q]["number"] ?> Details</h3>
                <a class="btn btn-primary pull-right" onclick="location.href='?uin=<?php echo $next; ?>';" data-toggle="modal" data-target="#editModal">
                  <i class="fa fa-arrow-right"></i><span href="?uin=<?php echo $next; ?>">Next</span>
                </a>
				 <a class="btn btn-primary pull-right" onclick="location.href='?uin=<?php echo $prev; ?>';" data-toggle="modal" data-target="#editModal">
                  <i class="fa fa-arrow-left"></i><span href="?uin=<?php echo $prev; ?>">Previous</span>
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
				<?php 
						$DodsId=$xml->Member[0]->attributes()->Dods_Id;
						if ($photos !== "screenshot") {
							
							if ($house === "Commons") {
									for($ii=0; $ii < $imagescount; $ii++) {
										if (trim($betaimages->member[$ii]->KnownAs) == trim($xml->Member[0]->DisplayAs)){
											$BetaId = $betaimages->member[$ii]->imageid;
										}
									}
									$imageurl = 'https://api20170418155059.azure-api.net/photo/'.$BetaId.'.jpeg?crop=MCU_3:2&width=1000&quality=80';
							}
							else { 
									$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
							}
						}
						else {
							$imageurl = 'images/'.$DodsId.'.jpg';
						}											
					?>
				
				<img src="<?php echo $imageurl; ?>" class="img-rounded main-question-image">
				</div>	
                <div class="list-group-item">
                  <label>Constituency</label>
                  <h4 class="list-group-item-heading"><?php echo $xml->Member[0]->MemberFrom ?></h4>
                </div>

                <div class="list-group-item">
                  <label>Question</label>
                  <h4 class="list-group-item-heading"><?php echo $qarray[$q]["text"]; ?></h4>
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
	<?php 
		else: 
		echo 'Please use the search tools </h3> 
		 </div>';
			
		endif; ?>
            
                
                 <div class="panel-footer">
                  <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
                </div>
              </div>


