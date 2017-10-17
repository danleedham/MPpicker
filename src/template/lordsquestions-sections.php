<option value="questions">Questions</option>
<?php

$LordsUrl = "http://www.lordswhips.org.uk/todays-lists/";
	// $LordsUrl = "http://leedhammedia.com/todays-lists/";
	$content = file_get_contents($LordsUrl);
	
	// Each list of speakers starts with the list
	$SplitOutSections = explode( '<p style="margin-left:36.0pt;"><strong>' , $content );
	// Remove the waffle at the beginning of the page
	$SplitOutSections = array_slice($SplitOutSections,1);	
	
	for($i=0; $i<count($SplitOutSections); $i++) {
		if(strpos($SplitOutSections[$i],"<em>Speakers</em>")) {
			$SplitOutSections[$i] = str_replace("followed by","",$SplitOutSections[$i]);
			$SplitOutSections[$i] = explode("</strong></p>",$SplitOutSections[$i]);
			$SplitOutSections[$i] = $SplitOutSections[$i][0];
		} else {
			$SplitOutSections[$i] = "";
		}
	}
	
	$SplitOutSections = array_values(array_filter($SplitOutSections));

	for($i=0; $i<count($SplitOutSections); $i++) {
		echo '<option value="'.$i.'">'.$SplitOutSections[$i].'</option>';
	}
?>