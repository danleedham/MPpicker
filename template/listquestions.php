<!DOCTYPE html>
<html lang="en">
<head>
<?php
$start = microtime(true);
$xmlDoc=new DOMDocument();

	//get parameters from URL
	if(!$qtype){$qtype=$_GET["type"];}
		if(!$qtype){$qtype="Substantive";}
	if(!$qdept){$qdept=$_GET["dept"];}
	if(!$date){$date=$_GET["date"];}
		if (!$date) {$date = date("Y-m-d");}
	if(!$groups){$groups=$_GET["groups"];}
	if(!$withdrawn){$withdrawn=$_GET["withdrawn"];}	
	$house = "Commons";
	$photos=$_GET["photos"];
	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&CommonsQuestionTime.QuestionType='.$qtype.'&_pageSize=500');
	$x=$xmlDoc->getElementsByTagName('item');
	$questionscount = $x->length;
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	$memberscount =  count($qxml);

	//Sort groups into a nice little 2D array
	if(intval($groups) !==0) {	
		$questiongroups = explode(',', $groups);
		$howmanygroups = count($questiongroups);

		foreach ($questiongroups as $value) {
				$groupssplit[] = explode(' ',$value);
		}
	}
	
	// Make the input withdrawn questions an array. Nicer for looping
	if(strlen($withdrawn)>=2){
		$withdrawnquestions = explode(' ', $withdrawn);
		$howmanywithdrawn = count($withdrawnquestions); 
	}	
	$time_elapsed_postload = microtime(true) - $start; 	
	// Arry with party ID and party color
	$colors = array("0"=>"#000000","4"=>"#0087DC","7"=>"#D46A4C","8"=>"#DDDDDD","15"=>"#DC241f","17"=>"#FDBB30","22"=>"#008142","29"=>"#FFFF00","30"=>"#008800","31"=>"#99FF66","35"=>"#70147A","38"=>"#9999FF","44"=>"#6AB023","47"=>"#FFFFFF");	
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
				$uin=$x->item($i)->getElementsByTagName('uin');
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
				
			   if($QuestionType[0]->textContent == "Topical"){
			   		$typeletter = 't';
			   } else {
			   		$typeletter = 's';
			   }
			   $typetext = $BallotNo[0]->textContent;
			   $qref = $typeletter.$typetext;		
				
			   $qarray[] = array( 'number'=>$BallotNo[0]->textContent,
								  'uin'=>$uin[0]->textContent,
								  'dept'=>$Department,
								  'text'=>$QText[0]->textContent,
								  'type'=>$QuestionType[0]->textContent,
								  'member'=>$CurrentQuestioner,
								  'DisplayAs'=>$DisplayAs,
								  'DodsId'=>$DodsId,
								  'MemberId'=>intval($MemberId),
								  'constituency'=>$Constituency,
								  'party'=>$party,
								  'color'=>$color,
								  'qref'=>$qref);
			}
		}
		
		// Function to sort questions by type then by number
		function compqs($a, $b) {
			if ($a['type'] == $b['type']) {
				return $a['number'] - $b['number'];
			}
			return strcmp($a['type'], $b['type']);
		}
		// Count how many questions there are
		$length = count($qarray);
		$time_elapsed_preloop = microtime(true) - $start; 	
	
	
	// If there are questions, sort the questions & generate list
	if ($length !== 0) {
			usort($qarray, 'compqs');
			
		// Generate the list of questions 	
		for($i=0; $i < $length; $i++) {
			
			// If no department is set or just has one, let's use the first one		
			if (!$qdept or count($deptarray) === 1) { $qdept = $qarray[0]["dept"]; }	
				if ($qarray[$i]["dept"] == $qdept) {		
					$currenti = $i;
					$iswithdrawn = '';
					$ingroup = '';
					if(strlen($withdrawn)>=2){
						if(in_array($qarray[$i]["qref"],$withdrawnquestions)){
							$iswithdrawn = ' withdrawn';
						}
					}
					// If there are groups then...
					if(intval($groups) !==0) {		
						// Check substantive questions for groups	
						if($qarray[$i]["type"] == "Substantive"){
							// Iterate through each group
							for($j=0; $j < $howmanygroups; $j++) {
								if(in_array($qarray[$i]["number"],$groupssplit[$j])){								
									$groupvisual= implode("+",$groupssplit[$j]);
									$ingroup = '<span class="ingroup"> '.$groupvisual.'</span>';
									$groupnumber = $j;
								}
							}
						}
					}
					
					// Finally render the HTML
					if ($hint=="") {
						$currenti = $i;
						$hint='<a id="q'.$qarray[$i]["uin"].'" class="list-group-item'.$iswithdrawn.'" onclick="load('.$qarray[$i]["uin"].','.'\''.$date.'\',\''.$photos.'\');return false;" href="#">
						   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
						   <h4 class="list-group-item-heading"> <span class="membername" style="color:'.$qarray[$i]["color"].'">'.strtoupper($qarray[$i]["qref"]).' '.$qarray[$i]["DisplayAs"].$ingroup.'</h4>
						   <p class="list-group-item-text">'.$qarray[$i]["constituency"].' ('.$qarray[$i]["party"].')</p></a><div id="firstquestion" style="display: none;">'.$qarray[$i]["uin"].'</div>';
					} 
					else {
						$currenti = $currenti;
						$hint=$hint .'<a id="q'.$qarray[$i]["uin"].'" class="list-group-item'.$iswithdrawn.'" onclick="load('.$qarray[$i]["uin"].','.'\''.$date.'\',\''.$photos.'\');return false;"  href="#">
						   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
						   <h4 class="list-group-item-heading"><span class="membername" style="color:'.$qarray[$i]["color"].'">'.strtoupper($qarray[$i]["qref"]).' '. $qarray[$i]["DisplayAs"].$ingroup.'</span></h4>
						   <p class="list-group-item-text">'.$qarray[$i]["constituency"].' ('.$qarray[$i]["party"].')</p></a>';
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
  else {
  		$ifdept = ' to '.$qdept;
  }
  
  $response='<a class="list-group-item">
			 <h4 class ="list-group-item-heading">No'.$iftype.' questions on '.$date.$ifdept.'</h4></a>';
} else {
	// Otherwise respond with the information required 	
    $response=$hint;
}	
    echo $howmanygroups.' groups: ';
    print_r($groupssplit);
    echo '<br />'.$howmanywithdrawn.' withdrawn: ';
    print_r($withdrawnquestions);
	echo $response;
?>	   
