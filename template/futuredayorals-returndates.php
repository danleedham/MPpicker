<?php
    /* 
    This file returns a list of dates available for Future Oral Questions
    Returns list of <option> if URL 'output' = "true"
    */
    
     if(!isset($outputDates) && isset($_GET["output"])){
        $outputDates = $_GET["output"];
    }
    // echo date('Y m d H i s');
    // This include returns $FOralsContent as a string and $FOralsUpdatedDate as a dateTime
	include("futuredayorals-loadpage.php");
	
	// Each date sentance starts with the <h2 class="date"> palava
	$SplitOutDays = explode('<h2 class="date">',$FOralsContent);	
	
	// Remove the waffle at the beginning of the page
	$SplitOutDays = array_slice($SplitOutDays,1);
	// For each date element, remove the rest of the bumf
	for($i=0; $i<count($SplitOutDays); $i++) {
		$SplitOutDays[$i] = explode('</h2>',$SplitOutDays[$i]);
		$SplitOutDays[$i] = $SplitOutDays[$i][0];
		$SplitOutDays[$i] = str_replace("Questions for Answer on ","",$SplitOutDays[$i]);
		if(date('F') == "December" && strpos($SplitOutDays[$i],"January")){    
		    $SplitOutDays[$i] = $SplitOutDays[$i] .' '. date('Y',strtotime('next year'));
	    } else {
	        $SplitOutDays[$i] = $SplitOutDays[$i] .' '. date('Y');
	    } 
		$SplitOutDays[$i] = strtotime($SplitOutDays[$i]);
		
	    if(isset($outputDates) && $outputDates = "true"){    
	        echo '<option value="'. $SplitOutDays[$i].'">'. date('l jS F',$SplitOutDays[$i]).'</option>';
	    }
	    $HasDates = true;
	}
	
    if(!isset($HasDates)) {
        echo '<option value="">No Dates Available</option>';
    }
?>