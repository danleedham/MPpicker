<?php
    /* 
        This file loads the future orals file into $FOralsContent
        and returns the updated date as $FOralsUpdatedDate
        To be included in other files    
    */
    
	// Load Future Orals URL
	$FOralsUrl = "https://publications.parliament.uk/pa/cm/cmfutoral/futoral.htm";
	$FOralsContent = file_get_contents($FOralsUrl);
	
	// Find the date the page was updated
	$GetUpdatedDate = explode('<p class="Into_Intro">',$FOralsContent);
	
	// The sentance we want ends with </p>
	$FOralsUpdated = explode('.</p>',$GetUpdatedDate[1]);
	$FOralsUpdatedString = $FOralsUpdated[0];
	
	// Now make the string a date	
	$FOralsUpdatedDate = strtotime(str_replace("Questions for oral answer on a future day as of ","",$FOralsUpdatedString));
	// print_r($UpdatedDate);
	
?>