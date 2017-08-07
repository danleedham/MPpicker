<?php
$xmlDoc=new DOMDocument();
if(!$date){$date = $_GET["date"];}
if(!$qdept){$qdept = $_GET["dept"];}
$depturl = str_replace(' ', '%20', $qdept);
$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&AnsweringBody='.$depturl.'&_pageSize=500');
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
				$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
				}	
					$qarray[] = array('type'=>$QuestionType[0]->textContent,
									  'dept'=>$Department);						  
					$typearray[] = array('type' => $QuestionType[0]->textContent);			
			}
		}
		// This gives us a unique list of question types
		if (count($typearray) !== 0) {
			$typearray = array_values(array_map("unserialize", array_unique(array_map("serialize", $typearray))));
		}
		// Remove any blank first elements
		if ($typearray[0]["type"] == '') {array_shift($typearray);}
		// If no type set, let's use the first none blank one
		if (!$qtype or count($typearray) === 1) { $qtype = $typesarray[0]["type"]; }
	?>
						<?php 
							foreach ($typearray as $key => $value) {
								if ($qtype == $value["type"]) { $istype = ' selected="selected" ';}
								else { $istype = "";}
								if ($value["type"]){
							   echo '<option'.$istype.' value="'. $value["type"].'">'. $value["type"].'</option>';
							   }
							} 
						?>
