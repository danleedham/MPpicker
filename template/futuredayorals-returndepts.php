<?php
    /* 
    This file returns a list of departments available for Future Oral Questions
    Input expected as date in format 1483916400
    Returns list of <option> if URL 'output' = "true"
    Can also return QuestionsByDepartment
    */
    
    if(!isset($date) && isset($_GET["date"])){
        $date = $_GET["date"];
    }
    
    if(!isset($outputDepts) && isset($_GET["output"])){
        $outputDepts = $_GET["output"];
    }	

    // This include returns $FOralsContent as a string and $FOralsUpdatedDate as a dateTime
	include("futuredayorals-loadpage.php");
	
	// Each date starts with the <h2 class="date"> palava
	$SplitOutDays = explode('<h2 class="date">',$FOralsContent);
	
	// Remove the waffle at the beginning of the page
	$SplitOutDays = array_slice($SplitOutDays,1);
	// We now have an array with each date split into a subarray
	
	// Just keep the content from the correct date
	$DayToKeep = array();
	
	if(!is_numeric($date)){
	    $date = strtotime($date);
	}
	for($i=0; $i<count($SplitOutDays); $i++) {
		$iSaveContent = $SplitOutDays[$i];
		$SplitOutDays[$i] = array();
		$SplitOutDays[$i]['content'] = $iSaveContent;
		$SplitOutDays[$i]['date'] = explode('</h2>',$SplitOutDays[$i]['content']);
		$SplitOutDays[$i]['date'] = $SplitOutDays[$i]['date'][0];
		$SplitOutDays[$i]['date'] = str_replace("Questions for Answer on ","",$SplitOutDays[$i]['date']);

		if($SplitOutDays[$i]['date'] == date('l j F',$date)){
		    $DayToKeep['date'] = $SplitOutDays[$i]['date'];
		    $DayToKeep['content'] = $SplitOutDays[$i]['content'];
		    $HasChosenDate = true;
		}
	}
	//print_r($SplitOutDays);
    if(isset($HasChosenDate)){
        // If it's the last date available, remove the waffle at the end of the page. 
        $Departments = explode("</div>",$DayToKeep['content']);
        $Departments = $Departments[0];
    
        // This should give us each department / type split
        $Departments = preg_split('/(id="fdo)+\d+(">)/',$Departments);
        array_shift($Departments);
        
        // Now we want to keep 
        $QuestionsByDepartment = array(); 
        
        for($i=0; $i<count($Departments); $i++) {
            $Departments[$i] = explode('</h3>',$Departments[$i]);
            $iSaveQuestions = str_replace('<h3 class="target"',"",$Departments[$i][1]);
            $Departments[$i] = $Departments[$i][0];
            $Departments[$i] = explode('Questions to the ',$Departments[$i]);
            $Departments[$i] = $Departments[$i][1];
            $QuestionsByDepartment[$i]['department'] = $Departments[$i]; 
            $QuestionsByDepartment[$i]['questions'] = $iSaveQuestions;
        }
        $Departments = array_unique($Departments);
        if(isset($outputDepts) && $outputDepts = "true"){       
            foreach ($Departments as $dept) {
                echo '<option value="'.$dept.'">'.$dept.'</option>';
            }
        }
    } else {
        echo '<option value="">No Departments Available for Date</option>';
    }
?>