
<select class="chosen-select my_select_box form-control custom-select mb-2 mr-sm-2 mb-sm-0" data-placeholder="Choose a Position..." id="pos-pick" tabindex="2">
	<?php
	if(!isset($house) && isset($_GET["house"])){
		$house = $_GET["house"];
	}
	if(!isset($house)){
		$house = "all";
	}

	if(!isset($side) && isset($_GET['side'])){
		$side = $_GET["side"];
	}
	if(!isset($side)){
		$side = "both";
	}
			
	if(isset($side) && $side !== "both") {
		if($side == "opposition") {
			$sideURL = "OppositionPosts";	
		} else {
			$sideURL = "GovernmentPosts";
		}	

	} else {
		$side = "both";
		$sideURL = "GovernmentPosts%7COppositionPosts";
	}
	
	$filename = "http://data.parliament.uk/membersdataplatform/services/mnis/members/query/house=".$house."%7CIsEligible=true/".$sideURL."/";


	$xmlDoc=new DOMDocument();
	$xmlDoc->load($filename);
	$x=$xmlDoc->getElementsByTagName('Member');
	$Jobsarray = array();
	for($i=0; $i<($x->length); $i++) {
		$Pos=$x->item($i)->getElementsByTagName('HansardName');				
		for($j=0; $j<($Pos->length); $j++) {
			$Jobsarray[]=$Pos->item($j)->nodeValue;
		}
	}
	$Jobsarray=array_filter(array_unique($Jobsarray, SORT_STRING));
	
	usort($Jobsarray, function($a, $b) {
		return $a <=> $b;
	});
	echo '<option value=""></option>';
	foreach ($Jobsarray as $value) {
	   echo '<option value="'. $value.'">'. $value.'</option>';
	  }
	?>
</select>