<!DOCTYPE html>
<html lang="en">
<head>
<?php
$start = microtime(true);
$xmlDoc=new DOMDocument();

	//get parameters from URL
	// If type isn't set above and it's passed in the URL get it, otherwise set it as Substantive
	if(!isset($qtype) && isset($_GET["type"])){
		$qtype=$_GET["type"];
	}
	if(!isset($qtype)){
		$qtype="Substantive";
	}
	
	// If Department isn't set above and it's passed in the URL get it
	if(!isset($qdept) && isset($_GET["dept"])){
		$qdept=$_GET["dept"];
	}
	
	// If the question date isn't set above and it's passed in the URL get it or set it to today
	if(!isset($date) && isset($_GET["date"])){
		$date=$_GET["date"];
	}
	if (!isset($date)) {
		$date = date("Y-m-d");
	}
		
	// If groups aren't set above and they're passed in the URL, get them
	if(!isset($groups) && isset($_GET["groups"])){
		$groups=$_GET["groups"];
		}
	
	// If withdrawn questions aren't set above and they're passed in the URL, get them
	if(!isset($withdrawn) && isset($_GET["withdrawn"])){
		$withdrawn=$_GET["withdrawn"];
	}	
	
	// For now questions are only searchable for the commons. In future we'll extend this
	if(!isset($house)){
		$house = "Commons";
	}
	
	if(isset($_GET["photos"])){
		$photos=$_GET["photos"];
	} else {
		$photos = "";
	}
	
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
	require_once('colors.php');	
	if ($questionscount == 1) {
			$hint = "";
	} else {	
		$hint="";
		for($i=0; $i<($x->length); $i++) {
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if (!isset($QText[0]->textContent)) {
			}
			else {
				$QuestionID=$x->item($i)->getElementsByTagName('ID');
				$uin=$x->item($i)->getElementsByTagName('uin');
				$tablingMemberPrinted=$x->item($i)->getElementsByTagName('tablingMemberPrinted');
					$CurrentQuestioner = trim($tablingMemberPrinted->item(0)->textContent);
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
				
			   if($Department == $qdept) {			
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
		}
		
		// Function to sort questions by type then by number
		function compqs($a, $b) {
				if ($a['type'] == $b['type']) {
					return $a['number'] - $b['number'];
				}
				return strcmp($a['type'], $b['type']);
		}
		// Count how many questions there are
		if(isset($qarray)){
			$length = count($qarray);
		} else {
			$length = 0;
		}
	
	// If there are questions, sort the questions & generate list
	if ($length !== 0) {
			usort($qarray, 'compqs');
	
		$hint = "";	
		// Make a mini array removing the withdrawn questions
		foreach ($qarray as $key => $value){
			if(!in_array($value["qref"],$withdrawnquestions)){
				$newarray[] = $value['uin'];
			}
		}
		$remaingquestions = count($newarray);

		// Generate the list of questions 	
		for($i=0; $i < $length; $i++) {
		
			// If it's the first question, don't let it try go previous
			if ($i == 0) {
				$prev = $qarray[$i]["uin"];
			} else {
				$prev = $qarray[$i-1]["uin"];
			}
										
			// If it's the last question don't let it try go to the next one
			if ($i == $length-1) {
				$next = $qarray[$i]["uin"];
			} else {
				$next = $qarray[$i+1]["uin"];
			}
			
			$deptcount = 0;
			if(isset($deptarray)){
				$deptcount = count($deptarray);
			}
			// If no department is set or just has one, let's use the first one		
			if (!isset($qdept) or $deptcount === 1) { 
				$qdept = $qarray[0]["dept"]; 
			}	
				if ($qarray[$i]["dept"] == $qdept) {		
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
					$hint=$hint .'<a id="q'.$qarray[$i]["uin"].'" class="list-group-item'.$iswithdrawn.'" onclick="load('.$qarray[$i]["uin"].','.'\''.$date.'\');return false;"  href="#">
					   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
					   <h4 class="list-group-item-heading">'.$ingroup.'<span class="partybox" style="background:'.$qarray[$i]["color"].'"></span>'.strtoupper($qarray[$i]["qref"]).' '. $qarray[$i]["DisplayAs"].'</h4>
					   <input type="hidden" id="next'.$qarray[$i]["uin"].'" value="'.$next.'"><input type="hidden" id="prev'.$qarray[$i]["uin"].'" value="'.$prev.'">
					   <p class="list-group-item-text">'.$qarray[$i]["constituency"].' ('.$qarray[$i]["party"].')</p></a>';
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
	echo $response;
?>	   
