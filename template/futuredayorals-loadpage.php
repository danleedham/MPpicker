<?php
    /* 
        This file loads the future orals file into $FOralsContent
        and returns the updated date as $FOralsUpdatedDate
        To be included in other files    
    */
    
    if(isset($_GET["date"])) {
		$date=$_GET["date"];
	}
	if(!isset($date)) {
		$date=$date = date("d-m-Y");
	}
	
	// Load Future Orals URL
	$context =  stream_context_create(
	                array(
	                    "http" =>   array(
	                        "header" => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
	                                )
	                )
	            );
	
	$dateForUrl = date("ymd", strtotime($date));        
	// Old URL "https://publications.parliament.uk/pa/cm/cmfutoral/futoral.htm";
	$FOralsUrl = "https://publications.parliament.uk/pa/cm201719/cmagenda/ob".$dateForUrl.".htm";
	$FOralsContent = file_get_contents($FOralsUrl, false, $context);
	
	//print_r($FOralsContent);
	
?>