<?php
$xmlDoc=new DOMDocument();

	// Get parameters from URL
	// If question type isn't set above and it's passed in the URL then get it, otherwise set it as Substantive
	if(!isset($qtype) && isset($_GET["type"])){
		$qtype=$_GET["type"];
	}
	if(!isset($qtype)){
		$qtype="Substantive";
	}
	
	// If Department isn't set above and it's passed in the URL then get it
	if(!isset($qdept) && isset($_GET["dept"])){
		$qdept=$_GET["dept"];
	}
	
	// If the question date isn't set above and it's passed in the URL, get it or set it to today
	if(!isset($date) && isset($_GET["date"])){
		$date=$_GET["date"];
	}
	if (!isset($date)) {
		$date = date("Y-m-d");
	}
	
	// Check if groups are to be grouped together
	if(!isset($grouptogether) && isset($_GET["together"])){
		$grouptogether=$_GET["together"];
	} else {
		$grouptogether = "together";
	}
		
	// If groups aren't set above and they're passed in the URL, get them
	if(!isset($groups) && isset($_GET["groups"])){
		$groups=$_GET["groups"];
	}
	
	// Sort groups into a nice little 2D array
	if(intval($groups) !==0) {	
		$questiongroups = explode(',', $groups);
		$howmanygroups = count($questiongroups);

		foreach ($questiongroups as $value) {
				$groupssplit[] = explode(' ',$value);
		}
	}
	
	// If there aren't any groups make sure the application knows
	if(!isset($howmanygroups)){
		$howmanygroups = 0; 
	}
		
	// If withdrawn questions aren't set above and they're passed in the URL, get them
	if(!isset($withdrawn) && isset($_GET["withdrawn"])){
		$withdrawn=strtolower($_GET["withdrawn"]);
	}		
		
	// Make the input withdrawn questions an array. Nicer for looping
	if(strlen($withdrawn)>=2){
		$withdrawnquestions = explode(' ', $withdrawn);
		$howmanywithdrawn = count($withdrawnquestions); 
	}	
	
	// For now questions are only searchable for the Commons. In future we'll extend this to the Lords too
	if(!isset($house)){
		$house = "Commons";
	}
	
	if(isset($_GET["photos"])){
		$photos=$_GET["photos"];
	} else {
		$photos = "";
	}
	
	// Load questions of the chosen date and of the chosen type
	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&CommonsQuestionTime.QuestionType='.$qtype.'&_pageSize=500');
	
	// Extract each question element (they're called 'items' in the XML)
	$x=$xmlDoc->getElementsByTagName('item');
	
	// Count how many question items we've loaded
	$questionscount = $x->length;
	
	// Load XML file containing all current MP's data 
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);

	// Array with party ID and party color
	require_once('colors.php');	
	
	// If there are no questions, an empty 'item' is presented. 
	// If there are more than one questions there are 2+ items. 
	$hint = "";
	if ($questionscount == 1) {
			$hint = "";
	} else {	
		
		// If beta images are loaded prior to this then skip
		if(!isset($feed)){
			$feed = file_get_contents("../betaimages.xml");
			$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
			$imagescount = count($betaimages);
		}

		// Now let's go through each question and extract the helpful bits of information
		for($i=0; $i<($x->length); $i++) {
			
			// If an item doesn't have the element questionText it's not actually a question
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if (!isset($QText[0]->textContent)) {
			} else {
				$QuestionID=$x->item($i)->getElementsByTagName('ID');
				$uin=$x->item($i)->getElementsByTagName('uin');
				
				// Some elements are subnodes and require the textContent to be extracted then trimmed
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
				$QuestionStatus=$x->item($i)->getElementsByTagName('QuestionStatus');	

				// Let's now check each MP to find which MP asked the question 
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
				$ballotnumber = $BallotNo[0]->textContent;
				$qref = $typeletter.$ballotnumber;	

				// Just a check to make sure our query got the questions from the right department
				if($Department == $qdept) {	
					// Now build an array with all the information we want	
				   $qarray[] = array( 'number'=>$BallotNo[0]->textContent,
									  'uin'=>$uin[0]->textContent,
									  'dept'=>$Department,
									  'text'=>$QText[0]->textContent,
									  'type'=>$QuestionType[0]->textContent,
									  'typeletter'=>$typeletter,
									  'member'=>$CurrentQuestioner,
									  'DisplayAs'=>$DisplayAs,
									  'DodsId'=>$DodsId,
									  'MemberId'=>intval($MemberId),
									  'constituency'=>$Constituency,
									  'party'=>$party,
									  'color'=>$color,
									  'qref'=>$qref,
									  'QuestionStatus'=>$QuestionStatus[0]->textContent
								);
				}
			}
		}
		
		// Count how many questions there are now after our checks (each question is an element in the array)
		if(isset($qarray)){
			$length = count($qarray);
		} else {
			$length = 0;
		}
		
		// Function to sort questions by type then by number
		function compqs($a, $b) {
				if ($a['type'] == $b['type']) {
					return $a['number'] - $b['number'];
				}
				return strcmp($a['type'], $b['type']);
		}
	
	if(isset($groupsplit)) { print_r($groupssplit); }
	// If there are questions, sort the questions & generate the list
	if ($length !== 0) {
		usort($qarray, 'compqs');
		
		// Let's remove any questions that have been withdrawn without notice (ie removed before the order paper is printed)
		$newqarray = array();
		
		for($i=0; $i < $length; $i++) {
			if ($qarray[$i]['QuestionStatus'] == "Withdrawn Without Notice"){	
			} else {
				// If the question status isn't withdrawn without notice add it to the list
				$newqarray[] = $qarray[$i];
			}				
		}
		
		if(isset($qarray)){
			$newlength = count($newqarray);
		} else {
			$newlength = 0;
		}
		
		// Now let's replace the qref with the new question number 
		for($i=0; $i < $newlength; $i++) {
			$newqarray[$i]["qref"] = $i+1;
		}
				
		// If requested, build a new array with gropued together
		if($grouptogether !== "dont") {
			$SortedArray = array();
		
			// Go through each question in the 'old' array
			for($i=0; $i < $newlength; $i++) {
				// If there are multiple groups, itterate over them		
				for($j=0; $j < $howmanygroups; $j++) {
					// If the question is at the beginning of a group, add it and all the rest of the questions from that group to the list
					if($newqarray[$i]["qref"] == $groupssplit[$j][0]) {
						foreach ($groupssplit[$j] as $question) { 
							$SortedArray[] = $newqarray[$question-1];
						}
					} 	
				}
			
				$IsThisADuplicate = "";
				for($j=0; $j < count($SortedArray); $j++){
					if($SortedArray[$j]["qref"] == $newqarray[$i]["qref"]) {
						$IsThisADuplicate = "Duplicate";
					}
				}
				if (!$IsThisADuplicate == "Duplicate") {
					$SortedArray[] = $newqarray[$i];
				}
			}				
			
			//print_r($SortedArray);
			$qarray = $SortedArray;
		
		} else { 
			$qarray = $newqarray;
		}
			
		$hint = "";	
		// Generate the list of questions 	
		for($i=0; $i < $newlength; $i++) {
		
			// If it's the first question, don't let it try go previous
			if ($i == 0) {
				$prev = $qarray[$i]["uin"];
			} else {
				$prev = $qarray[$i-1]["uin"];
			}
										
			// If it's the last question don't let it try go to the next one
			if ($i == $newlength-1) {
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
				if(isset($withdrawnquestions) && in_array($qarray[$i]["qref"],$withdrawnquestions)){
					$iswithdrawn = ' withdrawn';
				}
				// If there are groups then...
				if(intval($groups) !==0) {		
					// Check substantive questions for groups	
					if($qarray[$i]["type"] == "Substantive"){
						// Iterate through each group
						for($j=0; $j < $howmanygroups; $j++) {
							if(in_array($qarray[$i]["qref"],$groupssplit[$j])){								
								$groupvisual= implode("+",$groupssplit[$j]);
								$ingroup = '<span class="ingroup"> '.$groupvisual.'</span>';
								$groupnumber = $j;
							}
						}
					}
				}
				
				for($ii=0; $ii < $imagescount; $ii++) {
					if (trim($betaimages->member[$ii]->KnownAs) == $qarray[$i]["DisplayAs"]){
						$BetaId = $betaimages->member[$ii]->imageid;
					}
				}
				$imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
				if (isset($BetaId) && $BetaId == ""){
					$imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"];
				}
				
				$hint=$hint .'<a id="q'.$qarray[$i]["uin"].'" class="list-group-item'.$iswithdrawn.'" onclick="load('.$qarray[$i]["uin"].','.'\''.$date.'\');return false;"  href="#">
				   <img src="'.$imageurl.'" class="img-rounded mini-member-image pull-left">
				   <h4 class="list-group-item-heading">'.$ingroup.'<span class="partybox" style="background:'.$qarray[$i]["color"].'"></span>'.strtoupper($qarray[$i]["typeletter"]).$qarray[$i]["qref"].' '. $qarray[$i]["DisplayAs"].'</h4>
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
