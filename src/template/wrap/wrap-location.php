<?php

$xmlDoc=new DOMDocument();

if(!isset($date) && isset($_GET["date"])){
	$date = $_GET["date"];
}

$xmlDoc->load('http://lda.data.parliament.uk/tvprogrammes.xml?_view=basic&max-startDate='.$date.'T23:59:59Z&_sort=-startDate&_pageSize=25&_properties=startDate');
$x=$xmlDoc->getElementsByTagName('item');
for($i=0; $i<($x->length); $i++) {
	$label=$x->item($i)->getElementsByTagName('label');
	if (!isset($label[0]->textContent) or $label[0]->textContent =="" ) {
	} else {
		$Location=$x->item($i)->getElementsByTagName('label');
		$Location=trim($Location->item(0)->textContent);
		$href = str_replace("http://data.parliament.uk/resources/","",$x->item($i)->getattribute('href'));
		$ProgDate=$x->item($i)->getElementsByTagName('startDate');
		$ProgDate=strtotime($ProgDate->item(0)->textContent);
		// As the query by min date doesn't work... 
		if (date('Y-m-d',$ProgDate) == $date) {
			$locarray[] = array('loc' => $Location,
								'href' => $href,
								'date'=> $ProgDate );
		}
	}				  				
}
// This gives us a unique list of departments & question types
if (isset($locarray) && count($locarray) !== 0) {
	$locarray = array_values(array_map("unserialize", array_unique(array_map("serialize", $locarray))));
}
// Count how many unique departments/types there are
if(isset($locarray)){
	// Remove any blank first elements
	if ($locarray[0]["loc"] == '') {
		array_shift($locarray);
	}
	foreach ($locarray as $key => $value) {
		if ($value["loc"]){
	    echo '<option value="'. $value["href"].'">'. $value["loc"].'</option>';
	    $haslocations = true;
	   }
	}
}	

if(!isset($haslocations)) {
	echo '<option value="">No events for this date</option>';
}
?>
						