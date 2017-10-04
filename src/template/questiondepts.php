<?php
$xmlDoc=new DOMDocument();

if(!isset($date) && isset($_GET["date"])){
	$date = $_GET["date"];
}
$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&_pageSize=500');
$x=$xmlDoc->getElementsByTagName('item');
$questionscount = $x->length;
if ($questionscount == 1) {
} else {	
	for($i=0; $i<($x->length); $i++) {
		$QText=$x->item($i)->getElementsByTagName('questionText');
		if (!isset($QText[0]->textContent) or $QText[0]->textContent =="" ) {
		} else {
			$Dept=$x->item($i)->getElementsByTagName('AnsweringBody');
				$Department=trim($Dept->item(0)->textContent);
				$deptarray[] = array('dept' => $Department);
		}				  				
	}
}
// This gives us a unique list of departments & question types
if (isset($deptarray) && count($deptarray) !== 0) {
	$deptarray = array_values(array_map("unserialize", array_unique(array_map("serialize", $deptarray))));
}
// Count how many unique departments/types there are
if(isset($deptarray)){
	$deptscount = count($deptarray);

	// Remove any blank first elements
	if ($deptarray[0]["dept"] == '') {array_shift($deptarray);}
	// If no department or type set, let's use the first none blank one
	if (!isset($qdept) or count($deptarray) === 1) { 
		$qdept = $deptarray[0]["dept"]; 
	}

	foreach ($deptarray as $key => $value) {
		if ($qdept == $value["dept"]) { $isdept = ' selected="selected" ';}
		else { $isdept = "";}
		if ($value["dept"]){
	   echo '<option'.$isdept.' value="'. $value["dept"].'">'. $value["dept"].'</option>';
	   }
	}
}	
if((isset($deptscount) and $deptscount == 0) or !isset($deptarray)) {
	echo '<option value="None to display" disabled>None to dispaly</option>';
}	

				
?>
						