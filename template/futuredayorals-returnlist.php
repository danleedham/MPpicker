<?php
    /* 
    This file returns a list of questions
    Input expected as date in format 1483916400
    Returns list of <option> if URL 'output' = "true"
    Can also return QuestionsByDepartment
    */
    
	// If the question date isn't set above and it's passed in the URL, get it or set it to today
	if(!isset($date) && isset($_GET["date"])){
		$date=$_GET["date"];
	}
	if (!isset($date)) {
		$date = strtotime(date('j d F'));
	}
   
    if(!isset($outputList) && isset($_GET["outputList"])){
        $outputList = $_GET["outputList"];
    }
   
   	// If question type isn't set above and it's passed in the URL then get it, otherwise set it as Substantive
	/* if(!isset($qtype) && isset($_GET["type"])){
		$qtype=$_GET["type"];
	}
	if(!isset($qtype)){
		$qtype="Substantive";
	}
	if(isset($qtype)){
		if($qtype !=="all") {
			$qtypeURL = "&CommonsQuestionTime.QuestionType=".$qtype;
		} else {
			$qtypeURL = "";
		}
	} */
	
	$qtype = "all";
	
	// If Department isn't set above and it's passed in the URL then get it
	if(!isset($qdept) && isset($_GET["dept"])){
		$qdept=$_GET["dept"];
	} else {
		$qdept = "";
	}

	
	// Check if groups are to be grouped together
	if(!isset($grouptogether) && isset($_GET["together"])){
		$grouptogether=$_GET["together"];
	} else {
		$grouptogether = "together";
	}
	
	// Check if topicals are split by party or not
	if(!isset($topicalsbyparty) && isset($_GET["topicalsbyparty"])){
		$topicalsbyparty = $_GET["topicalsbyparty"];
	} else {
		$topicalsbyparty = "byparty";
	}
		
	// If groups aren't set above and they're passed in the URL, get them
	if(!isset($groups) && isset($_GET["groups"])){
		$groups=$_GET["groups"];
	} else {
	    $groups = "";
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
	} else {
	    $withdrawn = "";
	}		
		
	// Make the input withdrawn questions an array. Nicer for looping
	if(strlen($withdrawn)>=2){
		$withdrawnquestions = explode(' ', $withdrawn);
		$howmanywithdrawn = count($withdrawnquestions); 
	}	
	
	// If without notice questions aren't set above and they're passed in the URL, get them
	if(!isset($withoutnotice) && isset($_GET["withoutnotice"])){
		$withoutnotice=strtolower($_GET["withoutnotice"]);
	} else {
	    $withoutnotice = "";
	}
		
	// Make the input withdrawn questions an array. Nicer for looping
	if(strlen($withoutnotice)>=1){
		$withoutnoticequestions = explode(' ', $withoutnotice);
		$howmanywithoutnotice = count($withoutnoticequestions); 
	}	

	if(isset($_GET["photos"])){
		$photos=$_GET["photos"];
	} else {
		$photos = "";
	}

    // This include returns $FOralsContent as a string and $FOralsUpdatedDate as a dateTime
    // This include retuns $QuestionsByDepartment with an array: department,questions
    
	include("futuredayorals-questionlist.php");
		
    // Count how many questions there are now after our checks (each question is an element in the array)
    if(isset($qarray)){
        $length = count($qarray);
    } else {
        $length = 0;
    }
    
    // Function to sort questions by department type then by number
    function compqs($a, $b) {
    if ($a['dept'] == $b['dept']) {
            if ($a['type'] == $b['type']) {
                return $a['number'] - $b['number'];
            }
            return strcmp($a['type'], $b['type']);
        }
        return strcmp($a['dept'], $b['dept']);		
    }
	if(isset($groupsplit)) { 
		print_r($groupssplit); 
	}
	// If there are questions, sort the questions & generate the list
	if ($length !== 0) {
		usort($qarray, 'compqs');
		
		// Let's remove any questions that have been withdrawn without notice (ie removed before the order paper is printed)
		// Also remove questions that are passed as being withdrawn without notice
		$newqarray = array();
		
		for($i=0; $i < $length; $i++) {
			if ($qarray[$i]['QuestionStatus'] == "Withdrawn Without Notice"){	
			} else {
				if(isset($withoutnoticequestions) && in_array($qarray[$i]["qref"],$withoutnoticequestions)){
					// Remove the question
				} else {
					// If the question status isn't withdrawn without notice add it to the list
					$newqarray[] = $qarray[$i];
				}
			}				
		}
		
		if(isset($qarray)){
			$newlength = count($newqarray);
		} else {
			$newlength = 0;
		}
		
		// Now let's replace the qref with the new question number 
		// First let's make an array of all the departments
		$deptsArray = array();
		for($i=0; $i < $newlength; $i++) {
			$deptsArray[] = $newqarray[$i]["dept"];
		}
		$deptsArray = array_unique($deptsArray);
		$deptsArray = array_values($deptsArray);

		$deptsArrayCounts = $deptsArray;
		// Now for each question... 
		for($i=0; $i < $newlength; $i++) {
			// If it's topical make topicals add, if not, add substantive
			if($newqarray[$i]["typeletter"] == "t") {
				$t = 1;
				$s = 0;
			} else {
				$t = 0;
				$s = 1;
			}	
			// Find which element of the array we're editing...
			$deptNo = array_search($newqarray[$i]["dept"],$deptsArray);
			// If that element doesn't have any counts yet, lets set them both to zero
			if(!isset($deptsArrayCounts[$deptNo]["t"])){
				$deptsArrayCounts[$deptNo] = array("t" => 0,
												   "s" => 0);
			}
			// Now let's add one to either topical or substantive for the given department
			$deptsArrayCounts[$deptNo] = array("t" => intval($deptsArrayCounts[$deptNo]["t"])+$t,
											   "s" => intval($deptsArrayCounts[$deptNo]["s"])+$s); 
			// Generic counter
			$newqarray[$i]["qref"] = $i+1;
			// Assign the type number as the specific department and specific type
			$newqarray[$i]["typenumber"] = $deptsArrayCounts[$deptNo][$newqarray[$i]["typeletter"]];							
		}	
		// Now breathe. 
		
		
		// Let's reorder topical questions if requested
		if($topicalsbyparty !== "dont" && $qdept !== "all") {
			$TopicalsSorted = array();
			$HouseOverview = simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/HouseOverview/Commons/".$date."/");
			$GovernmentID = $HouseOverview->Party[0]->attributes()->Id;
			for($i=0; $i < $newlength; $i++) {
				if($qtype == "all" && $newqarray[$i]["typeletter"] == "s") {
					if($newqarray[$i]["dept"] !== "Prime Minister") {
						$TopicalsSorted[] = $newqarray[$i];
					}
				}
				if($newqarray[$i]["dept"] == "Prime Minister" or $newqarray[$i]["typeletter"] == "t") {
					if($newqarray[$i]["partyid"] == intval($GovernmentID) ){
						$TopicalsSorted[] = $newqarray[$i];
					}
				}	
			}
		
			// Now just shove the rest of the questions to the end of the new array
			for($i=0; $i < $newlength; $i++) {
				$AlreadyIn  = "";
				for($j=0; $j < count($TopicalsSorted); $j++) {
					if($TopicalsSorted[$j]["qref"] == $newqarray[$i]["qref"]) {
						$AlreadyIn = "Already In";
					}
				}
				if(!$AlreadyIn  == "Already In") {
					$TopicalsSorted[] = $newqarray[$i];
				}
			}
			// Make the topicals sorted array the newqarray 
			$newqarray = $TopicalsSorted;	
		}
		
		// If requested, build a new array with gropued together
		if($grouptogether !== "dont") {
			$SortedArray = array();
		
			// Go through each question in the 'old' array
			for($i=0; $i < $newlength; $i++) {
				// If there are multiple groups, itterate over them		
				for($j=0; $j < $howmanygroups; $j++) {
					// If the question is at the beginning of a group, add it and all the rest of the questions from that group to the list
					if($newqarray[$i]["qref"] == $groupssplit[$j][0] && $newqarray[$i]["type"] !== 'Topical') {
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
			$iswithdrawn = '';
			$ingroup = '';
			if(isset($withdrawnquestions) && in_array($qarray[$i]["typeletter"].$qarray[$i]["qref"],$withdrawnquestions)){
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
				if (intval($betaimages->member[$ii]->memberid) == $qarray[$i]["MemberId"]){
					$BetaId = $betaimages->member[$ii]->imageid;
				}
			}
			$imageurl = 'images/stock/thumbs/'.$BetaId.'.jpeg';
			if (isset($BetaId) && $BetaId == ""){
				$imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"];
			}
			
			$DeptTitle="";
			// If we're providing all the departments
			if($qdept == "all") {
				if($qarray[$i]["typenumber"] == 1) {
					$DeptTitle = '
					<div class="group-text-details">
						<h4 class="list-group-item-heading">'.$qarray[$i]["dept"].' - '.$qarray[$i]["type"].'</h4>
					</div>';
				}
			}
			
			$hint=$hint.$DeptTitle.'
				<a id="q'.$qarray[$i]["uin"].'" class="list-group-item list-section-list '.$iswithdrawn.'" onclick="futuredayoralsload('.$qarray[$i]["uin"].','.'\''.$date.'\');return false;"  href="#">
					<img src="'.$imageurl.'" class="mini-member-image pull-left">
					<h4 class="list-group-item-heading">'.$ingroup.'<span class="partybox" style="background:'.$qarray[$i]["color"].'!important"></span>'.strtoupper($qarray[$i]["typeletter"]).$qarray[$i]["typenumber"].' '. $qarray[$i]["DisplayAs"].'</h4>
					<input type="hidden" id="next'.$qarray[$i]["uin"].'" value="'.$next.'"><input type="hidden" id="prev'.$qarray[$i]["uin"].'" value="'.$prev.'">
					<p class="list-group-item-text">'.$qarray[$i]["constituency"].' ('.$qarray[$i]["party"].')</p>
				</a>';
			   
		}
	}  

// Set output if no questions were found or to the correct values
if ($hint=="") {
  
  /* if(!$_GET["type"]){ 
  		$iftype = '';
  }
  else {
  		$iftype = ' '.$qtype;
  } */
  
  if(!$qdept){$ifdept = '';}
  else {
  		$ifdept = ' to '.$qdept;
  }
  
  $response='<a class="list-group-item">
			 <h4 class ="list-group-item-heading">No questions on '.date('l j F',strtotime($date)).$ifdept.'</h4></a>';
} else {
	// Otherwise respond with the information required 	
    $response=$hint;
}	
	if($outputList == "true"){
	    echo $response;
	}

?>	   
