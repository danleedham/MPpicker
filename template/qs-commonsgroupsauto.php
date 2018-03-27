<?php
$xmlDoc=new DOMDocument();

if(!isset($date) && isset($_GET["date"])){
	$date = $_GET["date"];
}
if(!isset($qdept) && isset($_GET["dept"])){
	$qdept = $_GET["dept"];
}
if(!isset($echoOutput) && isset($_GET["output"])){
	$echoOutput = $_GET["output"];
}
if(isset($qdept)){
	if($qdept == "all") {
		$depturl = "";
	} else {
		$depturl = '&AnsweringBody='.str_replace(' ', '%20', $qdept);
	}
} else {
	$depturl ="";
}	

$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=basic&AnswerDate='.$date.'&CommonsQuestionTime.QuestionType=Substantive&_pageSize=100'.$depturl.'&_properties=CommonsQuestionTime.QuestionType,questionText,ballotNumber,QuestionStatus');
$x=$xmlDoc->getElementsByTagName('item');

$questionscount = $x->length;

$qarray = Array();

if ($questionscount == 1) {
} else {	
    for($i=0; $i<($x->length); $i++) {
        $questionText=$x->item($i)->getElementsByTagName('questionText');
        if (!isset($questionText[0]->textContent) or $questionText[0]->textContent == "" or strpos($questionText[0]->textContent,"official engagements")) {
        } else {
            $QuestionType=$x->item($i)->getElementsByTagName('QuestionType');	
            $ballotNumber=$x->item($i)->getElementsByTagName('ballotNumber');
            $withdrawn=$x->item($i)->getElementsByTagName('QuestionStatus');
            $qarray[] =  array('ballotNumber' => $ballotNumber[0]->nodeValue,
                               'text' => $questionText[0]->textContent,
                               'withdrawn' => $withdrawn[0]->textContent );
        }					  		
    }
}
if(count($qarray) !== 0){ 

    // Remove all withdrawn questions
    for($i=0; $i<count($qarray); $i++){
        if($qarray[$i]['withdrawn'] !=="Withdrawn Without Notice"){
            $newArray[] = $qarray[$i];
        }
    }

    $qarray = array_values($newArray);
    
    $duplicates = Array();

    foreach ($qarray as $current_key => $current_array) {
        foreach ($qarray as $search_key => $search_array) {
            if ($search_array['text'] == $current_array['text']) {
                if ($search_key != $current_key) {
                    $duplicates[] = $current_array;
                }
            }
        }
    }
    if(count($duplicates) > 0) {
        $uniques = Array();
        $groups = Array();
        // Group like questions
        usort($duplicates, function($a, $b) {
            return $a['text'] <=> $b['text'];
        });

        for($i=0; $i<count($duplicates); $i++){
            if(!in_array($duplicates[$i]['text'],$uniques)){
                $uniques[] = $duplicates[$i]['text'];
            }
            $currentNumberOfGroups = count($uniques);
            $groups[$currentNumberOfGroups-1][] = $duplicates[$i]['ballotNumber'];
        }

        for($i=0; $i<count($groups); $i++){
            $groups[$i] = array_values(array_unique($groups[$i]));
            usort($groups[$i], function($a, $b) {
                return $a <=> $b;
            });
        }
        // Now sort the groups so the group with the smallest first question come first
        usort($groups, function($a, $b) {
            return $a[0] <=> $b[0];
        });
    }
    if(isset($echoOutput)){
        foreach($groups as $value){
            echo implode(" ",$value);
            echo "\n";
        }
    }
}

?>
