<?php
$xmlDoc=new DOMDocument();

if(!isset($date) && isset($_GET["date"])){
	$date = $_GET["date"];
}
if(!isset($element) && isset($_GET["element"])){
	$element = $_GET["element"];
}
if (!isset($element)) {
	
	echo '<option value="">Please pick a location first</option>';
		
} else {

	$xmlDoc->load('http://lda.data.parliament.uk/resources/'.$element.'.xml?_view=basic&_properties=itemListElement.');

	// A list of chapters for the program are stored as items in the itemListElement
	$x=$xmlDoc->getElementsByTagName('itemListElement');
	$x=$x->item(0)->getElementsByTagName('item');
	for($i=0; $i<($x->length); $i++) {	
		$chapter=str_replace("http://","http://lda.",$x->item($i)->getattribute('href'));
		$ChapterNumber=trim(str_replace("http://data.parliament.uk/resources/".$element."/stackvideoitems/","",$x->item($i)->getattribute('href')));
		$ChaptXML=new DOMDocument();
		$ChaptXML->load($chapter.'.xml?_view=basic&_properties=description');
		$ChaptXML=$ChaptXML->getElementsByTagName('description');
		$ChapterTitle = trim($ChaptXML->item(0)->textContent);
		$chapterarray[] = array('chapter' => $ChapterTitle,
								'number' => $ChapterNumber);
	}	

		
	// This gives us a unique list of chapters
	if (isset($chapterarray) && count($chapterarray) !== 0) {
		$chapterarray = array_values(array_map("unserialize", array_unique(array_map("serialize", $chapterarray))));
	}
	
	usort($chapterarray, function($a, $b) {
				return ($a["number"] < $b["number"]);
			});
	
	// Count how many unique chapters there are
	if(isset($chapterarray)){
		$deptscount = count($chapterarray);

		// Remove any blank first elements
		if ($chapterarray[0]["chapter"] == '') {
			array_shift($chapterarray);
		}

		foreach ($chapterarray as $key => $value) {
			if ($value["chapter"]){
		   echo '<option value="'. $value["number"].'">'.$value["chapter"].'</option>';
		   }
		}
	}
}					
?>
						