<select class="chosen-select my_select_box form-control custom-select mb-2 mr-sm-2 mb-sm-0" data-placeholder="Choose a Committee..." id="committee-input" tabindex="2">
	<?php
	if(!isset($house) && isset($_GET["house"])){
		$house = $_GET["house"];
	}
	if(!isset($house)){
		$house = "all";
	}

	$xmlDoc=new DOMDocument();
	$xmlDoc->load('http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Committees/');
		$x=$xmlDoc->getElementsByTagName('Committee');
		$committeecount = $x->length;	
		if ($committeecount == 1) {
		} else {	
			for($i=0; $i<($x->length); $i++) {
					$EndDate=$x->item($i)->getElementsByTagName('EndDate');
						$EndString = $EndDate->item(0)->textContent;
					$IsCommons = $x->item($i)->getElementsByTagName('IsCommons');
					$IsLords = $x->item($i)->getElementsByTagName('IsLords');
					if ($IsCommons->item(0)->textContent == "True"){
						if($IsLords->item(0)->textContent == "True"){
							$currenthouse = "both";
						}
						else {
							$currenthouse = "Commons";
						}
					}
					elseif($IsLords->item(0)->textContent == "True"){
					$currenthouse = "Lords";
					}
					 
					if (!strtotime($EndString) >= time()) {
						if($currenthouse == $house or $house == "all"){
							$Name=$x->item($i)->getElementsByTagName('Name');
							$CommitteeName=trim($Name->item(0)->textContent);  
							$committeearray[] = $CommitteeName;
						}
					}			
			}
			sort($committeearray);			   
			echo '<option value="">All Committees</option>';
			foreach ($committeearray as $value) {
			   echo '<option value="'. $value.'">'. $value.'</option>';
			   }
		}
	?>
</select>