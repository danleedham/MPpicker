<?php

	if(!isset($id) && isset($_GET["id"])){
		$id=$_GET["id"];
		if($id !== "countspeakers") {
			$id = "currentspeakers";
		}
	} else {
		$id = "currentspeakers";
	}
	include 'wind-getclips.php';	
				
	echo '<input type="number" id="'.$id.'" value="'.count($Clips).'">';
?>