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
	include("qs-futuredayorals-loadpage.php");
	
	// Each date starts with the <h2 class="date"> palava
	$SplitOutDepts = explode('Oral Questions to',$FOralsContent);
	
	// Remove the waffle at the beginning of the page
	$SplitOutDepts = array_slice($SplitOutDepts,1);
	// We now have an array with each date split into a subarray
	
	for($i=0; $i<count($SplitOutDepts); $i++) {
		$iSave = $SplitOutDepts[$i];
		$SplitOutDepts[$i] = array();
		$SplitOutDepts[$i]['dept'] = explode('</p>',$iSave);
		$SplitOutDepts[$i]['dept'] = trim($SplitOutDepts[$i]['dept'][0]);
		$SplitOutDepts[$i]['content'] = $iSave;
	}

	$SplitOutDepts[count($SplitOutDepts)-1]['content'] = explode('<p class="paraBusinessSub-SectionHeading"',$SplitOutDepts[count($SplitOutDepts)-1]['content']);
    $SplitOutDepts[count($SplitOutDepts)-1]['content'] =  $SplitOutDepts[count($SplitOutDepts)-1]['content'][0];
    	
   	// print_r($SplitOutDepts);
   	
    $Departments = array();    
    $QuestionsByDepartment = array(); 
    for($i=0; $i<count($SplitOutDepts); $i++) {
        if(isset($SplitOutDepts[$i]['dept']) && $SplitOutDepts[$i]['dept'] !== "" ){
            $iSaveQuestions = $SplitOutDepts[$i]['content'];
            $Departments[] = $SplitOutDepts[$i]['dept'];
            $QuestionsByDepartment[$i]['department'] = $Departments[$i]; 
            $QuestionsByDepartment[$i]['questions'] = $iSaveQuestions;
        }
    }
    
    $Departments = array_unique($Departments);
    
    if(isset($outputDepts) && $outputDepts = "true"){       
        foreach ($Departments as $dept) {
            echo '<option value="'.$dept.'">'.$dept.'</option>';
        }
        if(count($Departments) > 0 ) {
            echo '<option value="all">All Departments</option>';
        }
    }
    
    //print_r($QuestionsByDepartment);
?>