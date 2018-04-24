<?php
$xmlDoc=new DOMDocument();

if(!isset($date) && isset($_GET["date"])){
	$date = $_GET["date"];
}
if(!isset($qdept) && isset($_GET["dept"])){
	$qdept = $_GET["dept"];
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

$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=basic&AnswerDate='.$date.'&_pageSize=100'.$depturl.'&_properties=CommonsQuestionTime.QuestionType,AnsweringBody');
$x=$xmlDoc->getElementsByTagName('item');
$questionscount = $x->length;		if ($questionscount == 1) {
} else {	
		for($i=0; $i<($x->length); $i++) {
			$ABody=$x->item($i)->getElementsByTagName('AnsweringBody');
			if (!isset($ABody[0]->textContent) or $ABody[0]->textContent == "") {
			} else {
				$Dept=$x->item($i)->getElementsByTagName('AnsweringBody');
				$Department=trim($Dept->item(0)->textContent);
				$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');	
				$typearray[] = array('type'=>$QuestionType[0]->textContent,
									  'dept'=>$Department);	
			}					  					  		
		}
	}
// This gives us a unique list of question type / department mixes
if (isset($typearray) && count($typearray) !== 0) {
	$typearray = array_values(array_map("unserialize", array_unique(array_map("serialize", $typearray))));

	// Remove any blank first elements
	if ($typearray[0]["type"] == '') {array_shift($typearray);}
	// If no type set, let's use the first none blank one
	if (!isset($qtype) or count($typearray) === 1) { 
		$qtype = $typearray[0]["type"]; 
	}

	foreach ($typearray as $key => $value) {
		if ($qtype == $value["type"]) { $istype = ' selected="selected" ';}
		else { $istype = "";}
		// If no department was set, let's take the first department
		if(!isset($qdept) or $qdept == "all") {
			$qdept = $value["dept"];
		}
		if($value["type"] and $value["dept"] == $qdept){
			echo '<option'.$istype.' value="'. $value["type"].'">'. $value["type"].'</option>';	   	
	   }
	}
	echo '<option value="all">All Types</option>'; 
}
	?>
