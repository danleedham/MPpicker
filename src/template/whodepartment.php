<?php

	echo '<option value=""></option>';
	echo '<option value="">All</option>';
$xmlDoc=new DOMDocument();
$xmlDoc->load('http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Departments/');
$x=$xmlDoc->getElementsByTagName('Department');
$departmentcount = $x->length;	
if ($departmentcount == 0) {
} else {	
	for($i=0; $i<($x->length); $i++) {
		$EndDate=$x->item($i)->getElementsByTagName('EndDate');
		$EndString = $EndDate->item(0)->textContent;					 
		if (!strtotime($EndString) >= time()) {
			$Name=$x->item($i)->getElementsByTagName('Name');
			$DepartmentName=trim($Name->item(0)->textContent);
			$Department_Id=$x->item($i)->getElementsByTagName('Department_Id')[0]->textContent;
			$departmentarray[] = array('Name' => $DepartmentName,
									   'Id' => $Department_Id);	
		}			
	}
	sort($departmentarray);	
	foreach ($departmentarray as $key => $value) {
		echo '<option value="'. $value["Id"].'">'. $value["Name"].'</option>';
	}
}
?>  

