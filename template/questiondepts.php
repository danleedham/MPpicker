<?php
$xmlDoc=new DOMDocument();
if(!$date){$date = $_GET["date"];}
$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&_pageSize=500');
	$x=$xmlDoc->getElementsByTagName('item');
	$questionscount = $x->length;
	if ($questionscount == 1) {
	}
	else {	
		for($i=0; $i<($x->length); $i++) {
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if ($QText[0]->textContent=="") {
			}
			else {
				$Dept=$x->item($i)->getElementsByTagName('AnsweringBody');
					$Department=trim($Dept->item(0)->textContent);
				}				  
				$deptarray[] = array('dept' => $Department);  			
			}
		}
		// This gives us a unique list of departments & question types
		if (count($deptarray) !== 0) {
			$deptarray = array_values(array_map("unserialize", array_unique(array_map("serialize", $deptarray))));
		}
		// Count how many unique departments/types there are
		$deptscount = count($deptarray);
		
		// Remove any blank first elements
		if ($deptarray[0]["dept"] == '') {array_shift($deptarray);}
		// If no department or type set, let's use the first none blank one
		if (!$qdept or count($deptarray) === 1) { $qdept = $deptsarray[0]["dept"]; }
 
							foreach ($deptarray as $key => $value) {
								if ($qdept == $value["dept"]) { $isdept = ' selected="selected" ';}
								else { $isdept = "";}
								if ($value["dept"]){
							   echo '<option'.$isdept.' value="'. $value["dept"].'">'. $value["dept"].'</option>';
							   }
							}
						?>