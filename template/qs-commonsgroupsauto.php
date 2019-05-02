<?php
$xmlDoc=new DOMDocument();

// Get paramaters from URL if passed, date, dept and output
if(!isset($date) && isset($_GET["date"])){
	$date = $_GET["date"];
}
if(!isset($qdept) && isset($_GET["dept"])){
	$qdept = $_GET["dept"];
}
// If output is set to anything we'll echo the output
if(!isset($echoOutput) && isset($_GET["output"])){
	$echoOutput = $_GET["output"];
}
if(isset($qdept)){
	if($qdept == "all") {
		$depturl = "";
		$deptProperty = ",AnsweringBody";
	} else {
		$depturl = '&AnsweringBody='.str_replace(' ', '%20', $qdept);
		$deptProperty = "";
	}
} else {
	$depturl = "";
	$deptProperty = ",AnsweringBody";
}	


// Go grab the data from Parliament and pop it into $xmlDoc
$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=basic&AnswerDate='.$date.'&CommonsQuestionTime.QuestionType=Substantive&_pageSize=100'.$depturl.'&_properties=CommonsQuestionTime.QuestionType,questionText,ballotNumber,QuestionStatus,unstarredQuestion'.$deptProperty);

// Make an accessible, loopable list of questions as $x
$x=$xmlDoc->getElementsByTagName('item');

$questionscount = $x->length;

$qarray = Array();

// If there is just one 'item' it is always a message to say there aren't any questions
if ($questionscount == 1) {
} else {	

    // Loop through our list of questions
    for($i=0; $i<($x->length); $i++) {
    
        // Get text of the question for that question
        $questionText=$x->item($i)->getElementsByTagName('questionText');
        
        // If there isn't any question text for a particular $x 'item' then it isn't a question so skip it
        if (!isset($questionText[0]->textContent) or $questionText[0]->textContent == "" or strpos($questionText[0]->textContent,"official engagements")) {
        } else {
        
            // Make the retured XML a little more family friendly 
            $QuestionType=$x->item($i)->getElementsByTagName('QuestionType');	
            $ballotNumber=$x->item($i)->getElementsByTagName('ballotNumber');
            $withdrawn=$x->item($i)->getElementsByTagName('QuestionStatus');
            $unstarredQuestion=$x->item($i)->getElementsByTagName('unstarredQuestion');
            if(!isset($qdept)) {
                $AnsweringBody=$x->item($i)->getElementsByTagName('AnsweringBody');
                $AnsweringBody = trim($AnsweringBody[0]->textContent);
            } else {
                $AnsweringBody = "";
            }
            // Build a nice array holding the important question info and add it to the others we've already made
            $qarray[] =  array('ballotNumber' => $ballotNumber[0]->nodeValue,
                               'text' => $questionText[0]->textContent,
                               'AnsweringBody' => $AnsweringBody,
                               'withdrawn' => $withdrawn[0]->textContent,
                               'unstarredQuestion' => $unstarredQuestion[0]->textContent );
                            
        }					  		
    }
}

if(count($qarray) !== 0){ 

    // Remove all withdrawn questions
    for($i=0; $i<count($qarray); $i++){
        if($qarray[$i]['withdrawn'] !=="Withdrawn Without Notice" && $qarray[$i]['unstarredQuestion'] !== 'true'){
            $newArray[] = $qarray[$i];
        }
    }
    
     // Sort questions by ballot number as they're not returned like this
     usort($newArray, function($a, $b) {
            return $a['ballotNumber'] <=> $b['ballotNumber'];
     });
     
    // We'll use this array as a counter, so that we can make new question numbers removing the withdrawn questions 
    $countDepts = Array();
    
    // Add 'question number... which we'll use now instead of Ballot Number
    for($i=0; $i<count($newArray); $i++){
        
        // If we have only asked for one department's questions, we'll just sequentially add number
        if(isset($qdept)){
            $newArray[$i]['questionNumber'] = $i+1  ;
        } else {
            
            // For multiple departments we have to make a count per department...
            $countDepts[$i]['dept'] = $newArray[$i]['AnsweringBody'];
            
            $counter = 0;
            
            // For each department... 
            for($j=0; $j<count($countDepts); $j++){
                if($countDepts[$j]['dept'] == $newArray[$i]['AnsweringBody']){
                    $counter = $counter + 1;
                }
            }    
            $newArray[$i]['questionNumber'] = $counter;
            
        }
    }
    
    // If there are any array values that aren't sequential, fix that
    $qarray = array_values($newArray);
    
    $duplicates = Array();
    
    // Only add the questions with duplicate text values to the $duplicates array
    foreach ($qarray as $current_key => $current_array) {
        foreach ($qarray as $search_key => $search_array) {
            if ($search_array['text'] == $current_array['text']) {
                if ($search_key != $current_key) {
                    $duplicates[] = $current_array;
                }
            }
        }
    }
    
    
    // If we have any duplicate questions 
    if(count($duplicates) > 0) {
        $uniques = Array();
        $groups = Array();
        
        // Group like questions
        if(!isset($qdept)){ 
            array_multisort(array_column($duplicates, 'AnsweringBody'), SORT_ASC,
                            array_column($duplicates, 'text'), SORT_ASC, 
                            $duplicates);
        } else {
            usort($duplicates, function($a, $b) {
                return $a['text'] <=> $b['text'];
            });
        }
         
        // for each duplicate question
        for($i=0; $i<count($duplicates); $i++){
            if(!in_array($duplicates[$i]['text'],$uniques)){
                $uniques[] = $duplicates[$i]['text'];
            }
            // Keep the number of unique groups here
            $currentNumberOfGroups = count($uniques);
            
            // build the $groups array with question numbers
            if(!isset($qdept)){
                $groups[$currentNumberOfGroups-1][] = $duplicates[$i]['AnsweringBody'].' '.sprintf("%02d",$duplicates[$i]['questionNumber']);
            } else {
                $groups[$currentNumberOfGroups-1][] = $duplicates[$i]['questionNumber'];
            }
        }
        
        for($i=0; $i<count($groups); $i++){
            $groups[$i] = array_values(array_unique($groups[$i]));
            usort($groups[$i], function($a, $b) {
                return $a <=> $b;
            });
        }
        // Now sort the groups so the group with the smallest first question come first
        if(!isset($qdept)){

        } else {
            usort($groups, function($a, $b) {
                return $a[0] <=> $b[0];
            });
        }
    }
    // If the user has requested an output, let's give them the groups as we'd ask for them later
    if(isset($echoOutput)){
        // Used for Debugging
        /* if(isset($qdept)){
            echo $qdept . "\n";
        } */
        $echo = "";
        foreach($groups as $value){
            $echo = $echo . implode(" ",$value);
            $echo = $echo . "\n";
        }
        if(!isset($qdept)){
            // echo 'No Department Selected '."\n";
        }
        echo trim($echo); // Gets rid of the last empty line  
    }
}

?>
